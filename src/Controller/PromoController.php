<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Repository\PromoRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Apprenant;
use App\Entity\Groupe;
use App\Service\UploadService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PromoController extends AbstractController
{
    private $encoder;
    private $validator;
    private $em;
    private $serializer;
    private $denormalizer;
    private $uploadSer;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        DenormalizerInterface $denormalizer,
        UploadService $uploadSer
    ) {
        $this->encoder = $encoder;
        $this->serializer = $serializer;
        $this->em = $em;
        $this->validator = $validator;
        $this->uploadSer = $uploadSer;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @Route(
     *      path="api/admin/promos",
     *      name="get_groupes",
     *      methods="GET",
     *      defaults={
     *          "_controller"="\App\PromoController::getAllPromos",
     *           "_api_resource_class"=Promo::class,
     *           "_api_collection_operation_name"="get_promos"
     *      }
     * )
     */
    public function getAllPromos(PromoRepository $repo){
        $promos = $repo->findAll();
        return $this->json($promos, Response::HTTP_OK, [], ["groups" => ["promo_read"]]);
    }

    /**
     * @Route(
     *      path="api/admin/promos/principal",
     *      name="promo_groupe_principal_all",
     *      methods="GET",
     *      defaults={
     *          "_controller"="\App\PromoController::getPromoGroupePrincipalAll",
     *           "_api_resource_class"=Promo::class,
     *           "_api_collection_operation_name"="get_promos_principal"
     *      }
     * )
     */
    public function getPromoGroupePrincipalAll(PromoRepository $repo){
        $promos = $repo->findByGroup("principal");
        return $this->json($promos, Response::HTTP_OK, [], ["groups" => ["promo_principal_read"]]);
    }

    /**
     * @Route(
     *      path="api/admin/promos/apprenants/attente",
     *      name="get_promos_apprenant",
     *      methods="GET",
     *      defaults={
     *          "_controller"="\App\PromoController::getAllPromoApprenantsAttente",
     *           "_api_resource_class"=Promo::class,
     *           "_api_collection_operation_name"="get_promos_apprenant"
     *      }
     * )
     */
    public function getAllPromoApprenantsAttente(PromoRepository $repo){
        $promos = $repo->findAppAttente();
        return $this->json($promos, Response::HTTP_OK, [], ["groups" => ["promo_apprenant_attente"]]);
    }

    /**
     * @Route(
     *      path="api/admin/promos/{id}",
     *      name="get_promo",
     *      methods="GET",
     *      defaults={
     *          "_controller"="\App\PromoController::getPromo",
     *           "_api_resource_class"=Promo::class,
     *           "_api_item_operation_name"="get_promo"
     *      }
     * )
     */
    public function getPromo(PromoRepository $repo, int $id){
        $promos = $repo->find($id);
        return $this->json($promos, Response::HTTP_OK, [], ["groups" => ["promo_read"]]);
    }

    /**
     * @Route(
     *      path="api/admin/promos/{id}/apprenants/attente",
     *      name="get_promo_apprenant",
     *      methods="GET",
     *      defaults={
     *          "_controller"="\App\PromoController::getPromoApprenantsAttente",
     *           "_api_resource_class"=Promo::class,
     *           "_api_item_operation_name"="get_promo_apprenant"
     *      }
     * )
     */
    public function getPromoApprenantsAttente(PromoRepository $repo, int $id){
        $promos = $repo->findAppAttenteById($id);
        return $this->json($promos, Response::HTTP_OK, [], ["groups" => ["promo_apprenant_attente"]]);
    }

    /**
     * @Route(
     *      path="api/admin/promos/{id}/principal",
     *      name="promo_groupe_principal",
     *      methods="GET",
     *      defaults={
     *          "_controller"="\app\PromosController::getPromoGroupePrincipal",
     *           "_api_resource_class"=Promo::class,
     *           "_api_item_operation_name"="get_promo_principal"
     *      }
     * )
     */
    public function getPromoGroupePrincipal(PromoRepository $repo, $id){
        $promo = $repo->findOneByGroup("principal", $id);
        // dd($promo);
        return $this->json($promo, Response::HTTP_OK, [], ["groups" => ["promo_principal_read"]]);
    }

    /**
     * @Route(
     *      path="/api/admin/promos", 
     *      name="add_promo",
     *      methods="POST",
     *      defaults={
     *           "_controller"="\app\PromoController::addPromo",
     *           "_api_resource_class"=Promo::class,
     *           "_api_collection_operation_name"="add_promo"
     *      }
     * )
     */
    public function addPromo(Request $req, \Swift_Mailer $mailer, ProfilRepository $profilRepo)
    {
        $data = $req->request->all();
        if($req->files->get("avatar")){
            $data["avatar"] = fopen(($req->files->get("avatar")), "rb");
        }

        $data["referentiel"] = json_decode($data['referentiel'], true);
        $data["formateurs"] = json_decode($data["formateurs"], true);
        $data["groupes"] = json_decode($data["groupes"], true);

        $promo = $this->denormalizer->denormalize($data, Promo::class, null, ["groups" => "promo_write"]);

        // dd($promo);

        if (count($promo->getGroupes()) > 1) {
            return new JsonResponse("Ajouter un seul groupe principal",Response::HTTP_BAD_REQUEST,[],true);
        }else {
            $promo->getGroupes()[0]->setType("principal");
            $profil = $profilRepo->findOneBy(["libelle" => "apprenant"]);
            if($req->files->get("apprenants")) {
                foreach($this->readCsv($req->files->get("apprenants")) as $app){
                    $promo->getGroupes()[0]->addApprenant($app);
                }
            }
            // dd($promo->getGroupes()[0]->getApprenants());
            foreach($promo->getGroupes()[0]->getApprenants() as $app){
                $password = "test";
                $app
                    ->setProfil($profil)
                    ->setPassword($this->encoder->encodePassword($app, $password));
                // $message = (new \Swift_Message("Admission Sonatel Academy"))
                //     ->setFrom("damanyelegrand@gmail.com")
                //     ->setTo($app->getEmail())
                //     ->setBody("Bonjour vous êtes selectionnés à la ". $promo->getTitre() ." de la Sonatel Academy.\nNous vous souhaitons la bienvenue et vous prions de suivre ce lien afin de confirmer votre admission.\nMerci.\nVotre mot de passe: ".$password);
                // // dd($mailer);
                // $mailer->send($message);
            }
            // dd($promo->getGroupes()[0]);
        }

        // dd($promo->getGroupes()[0]->getApprenants());

        if(!$this->isGranted('PROMO_CREATE', $promo)){
            return $this->json([
                "message" => "Vous n'avez pas accès à cette ressource"
            ], Response::HTTP_FORBIDDEN);
        }

        $errors = $this->validator->validate($promo);
        if ($errors){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        
        // dd($promo);

        $this->em->persist($promo);
        $this->em->flush();
        return $this->json("Promo ajoutée avec succès", Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *      path="/api/admin/promos/{id}", 
     *      name="update_promo",
     *      methods="PUT",
     *      defaults={
     *           "_controller"="\app\PromosController::updatePromo",
     *           "_api_resource_class"=Promo::class,
     *           "_api_item_operation_name"="update_promo"
     *      }
     * )
     */
    public function updatePromo(Request $req, PromoRepository $repo, int $id)
    {
        // $ref = json_decode($req->getContent(), true);
        $promo = $repo->find($id);
        if($promo && !$promo->getIsDeleted()) {
            $promoTab = $this->uploadSer->getContentFromRequest($req, "avatar");
            // dd($promoTab);
            foreach($promoTab as $key => $value) {
                // dump($key);
                if($key !== "referentiel" || $key !== "formateurs" || $key !== "groupes") {
                    $method = 'set'.ucfirst($key);
                    if(method_exists($promo, $method)) {
                        $promo->{$method}($value);
                    }
                }
            }
            // dd("ok");
        }
        $this->em->flush();
        // if(!empty($ref["referentiel"]) and isset($ref["referentiel"]["id"])){
        //     if($ref["referentiel"]["id"] === $promo->getReferentiel()->getId()){
        //         if(isset($ref["referentiel"]["libelle"])){
        //             $promo->getReferentiel()->setLibelle($ref["referentiel"]["libelle"]);
        //         }
        //     }
        // }
        return $this->json($promo, Response::HTTP_OK);
    }

    private function generatePassword(int $taille)
    {
        // Liste des caractères possibles
        $cars="azertyiopqsdfghjklmwxcvbn0123456789";
        $mdp='';
        $long=strlen($cars);

        srand((double)microtime()*1000000); 
        //Initialise le générateur de nombres aléatoires

        for ($i=0; $i<$taille; $i++){
            $mdp=$mdp.substr($cars,rand(0,$long-1),1);
        }

        return $mdp;
    }

    private function readCsv($file) {
        if(($fp = fopen($file, "r"))  !== false) {
            $apps = [];
            while (($rows = fgetcsv($fp, 1024)) !== false ) {
                $tabApprenants[] = $rows;
                // $line[] = ;
            }
            fclose($fp);
            for ($j=1; $j<sizeof($tabApprenants); $j++) {
                $apprenant = new Apprenant();
                for ($i=0; $i<sizeof($tabApprenants[$j]); $i++) {
                    $method = 'set'.ucfirst($tabApprenants[0][$i]);
                    if (method_exists($apprenant, $method)) {
                        $apprenant->{$method}($tabApprenants[$j][$i]);
                    }
                }
                $apps[] = $apprenant;
                // dump($apprenant);
            }
            // dd($tabApprenants);
        }
        return $apps;
    }
}
