<?php

namespace App\Service;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use App\Service\UploadService;
use App\Repository\ProfilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $profilRepo;
    private $encoder;
    private $denormalizer;
    private $validator;

    public function __construct(
        UploadService $uploadService,
        ProfilRepository $profilRepo,
        UserPasswordEncoderInterface $encoder,
        DenormalizerInterface $denormalizer,
        ValidatorInterface $validator
    )
    {
        $this->uploadService = $uploadService;
        $this->profilRepo = $profilRepo;
        $this->encoder = $encoder;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
    }

    public function createUser(Request $req)
    {
        $userTab = $req->request->all();
        $userTab["avatar"] = fopen(($req->files->get("avatar")), "rb");
        // dd($req->files->get("avatar"));
        $profil = $this->profilRepo->find($userTab['profil']);
        if($profil && !$profil->getIsDeleted() && $profil->getLibelle() !== 'apprenant'){
            $type = 'App\Entity\User';
            if($profil->getLibelle() === 'cm'){
                $type = 'App\Entity\CommunityManager';
            }elseif($profil->getLibelle() === 'formateur'){
                $type = 'App\Entity\Formateur';
            }
            unset($userTab["profil"]);
            // dd($userTab);
            $user = $this->denormalizer->denormalize($userTab, $type);
            $user
                ->setProfil($profil)
                ->setPassword($this->encoder->encodePassword($user, "Test"));
        }else{
            return new JsonResponse(["message" => "Le profil est introuvable"], Response::HTTP_BAD_REQUEST);
        }
        // dd($user);
        $errors = $this->validator->validate($user);
        if($errors){
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        return $user;
    }
    
    public function updateUser($user, array $data)
    {
        foreach($data as $key => $value) {
            if($key !== 'profil') {
                $method = 'set'.ucfirst($key);
                if(method_exists($user, $method)) {
                    $user->{$method}($value);
                }
            }
        }

        $errors = $this->validator->validate($user);
        if($errors){
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        return $user;
    }

}