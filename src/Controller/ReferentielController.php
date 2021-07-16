<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Validator;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Referentiel;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\ReferentielRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ReferentielController extends AbstractController
{
    private $denormalizer;
    private $validator;
    private $em;
    private $uploadSer;

    public function __construct(
        DenormalizerInterface $denormalizer,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        UploadService $uploadSer
    ) {
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
        $this->em = $em;
        $this->uploadSer = $uploadSer;
    }
    
    /**
     * @Route(
     *      path="api/admin/referentiels",
     *      name="add_referentiel",
     *      methods="POST",
     *      defaults={
     *          "_controller"="App\ReferentielController::createRef",
     *          "_api_resource_class"=Referentiel::class,
     *          "_api_collection_operation_name"="add_referentiel"
     *      }
     * )
     */
    public function createRef(Request $req): Response
    {
        $data = $req->request->all();
        // dd($req->files->get("programme"));
        if ($req->files->get("programme")) {
            $data["programme"] = fopen(($req->files->get("programme")), "rb");
        }

        $data["groupeCompetences"] = json_decode($data["groupeCompetences"], true);
        $ref = $this->denormalizer->denormalize($data, 'App\Entity\Referentiel', null, ["groups" => "ref_write"]);
        // dd($ref);
        
        $errors = $this->validator->validate($ref);
        if($errors){
            return $this->json($ref, Response::HTTP_BAD_REQUEST);
        }
        
        $this->em->persist($ref);
        $this->em->flush();
        
        // dd($ref);
        return $this->json($ref, Response::HTTP_CREATED, [], ["groups" => "ref_read"]);
    }

    /**
     * @Route(
     *      path="api/admin/referentiels/{id}",
     *      name="update_referentiel",
     *      methods="PUT",
     *      defaults={
     *          "_controller"="App\ReferentielController::updateRef",
     *          "_api_resource_class"=Referentiel::class,
     *          "_api_item_operation_name"="update_referentiel"
     *      }
     * )
     */
    public function updateRef(Request $req, int $id, ReferentielRepository $refRepo, GroupeCompetenceRepository $grpcRepo): Response
    {
        $ref = $refRepo->find($id);
        $data = $this->uploadSer->getContentFromRequest($req, "programme");
        $data["groupeCompetences"] = json_decode($data["groupeCompetences"]);

        foreach($data as $key => $value) {
            // dump($key, $value);
            if($key === "groupeCompetences") {
                foreach($ref->getGroupeCompetences() as $grpc) {
                    $ref->removeGroupeCompetence($grpc);
                }
                foreach($data[$key] as $grpc) {
                    $ref->addGroupeCompetence($grpcRepo->find($grpc->id));
                }
            }else {
                $method = 'set'.ucfirst($key);
                if(method_exists($ref, $method)) {
                    $ref->{$method}($value);
                }
            }
        }
        
        $errors = $this->validator->validate($ref);
        if($errors){
            return $this->json($ref, Response::HTTP_BAD_REQUEST);
        }

        $this->em->flush();
        
        return $this->json($ref, Response::HTTP_CREATED, [], ["groups" => "ref_read"]);
    }
}
