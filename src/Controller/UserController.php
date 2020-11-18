<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route(
     *      name="create_user",
     *      path="/api/admin/users",
     *      methods="POST",
     *      defaults={
     *          "_controller"="\App\UserController::createUser",
     *          "_api_resource_class"=User::class,
     *          "_api_collection_operation_name"="create_users"
     *      }
     * )
     */
    public function createUser(
        Request $req,
        ProfilRepository $repoProfil,
        DenormalizerInterface $denormalizer,
        UserPasswordEncoderInterface $encoder,
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ) {
        $userTab = $req->request->all();
        $userTab["avatar"] = fopen(($req->files->get("avatar")), "rb");
        if(isset($userTab["profil"]) && !empty($userTab["profil"])){
            $profil = $repoProfil->find($userTab["profil"]);
            if($profil){
                $type = 'App\Entity\User';
                if($profil->getLibelle() == "formateur"){
                    $type = 'App\Entity\Formateur';
                }elseif($profil->getLibelle() == "apprenant"){
                    $type = 'App\Entity\Apprenant';
                }
                unset($userTab["profil"]);
                $user = $denormalizer->denormalize($userTab, $type);
                $user
                    ->setProfil($profil)
                    ->setPassword($encoder->encodePassword($user, "Test"));
            }else{
                return $this->json(["message" => "Le profil est introuvable"], Response::HTTP_BAD_REQUEST);
            }
        }else{
            return $this->json(["message" => "L'id du profil est obligatoire"], Response::HTTP_BAD_REQUEST);
        }
        $errors = $validator->validate($user);
        if($errors){
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $em->persist($user);
        $em->flush();
        return $this->json($user, Response::HTTP_CREATED);
    }
}