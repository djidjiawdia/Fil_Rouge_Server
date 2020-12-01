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

    public function createUser(Request $req, string $attr, string $type = User::class)
    {
        $userTab = $req->request->all();
        $userTab["avatar"] = fopen(($req->files->get("avatar")), "rb");
        $profil = $this->profilRepo->findOneBy(["libelle" => $attr]);
        if($profil && !$profil->getIsDeleted()){
            $user = $this->denormalizer->denormalize($userTab, $type);
            $user
                ->setProfil($profil)
                ->setPassword($this->encoder->encodePassword($user, "Test"));
        }else{
            return new JsonResponse(["message" => "Le profil est introuvable"], Response::HTTP_BAD_REQUEST);
        }
        
        $errors = $this->validator->validate($user);
        if($errors){
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        return $user;
    }
    
    public function updateUser($user, array $data)
    {
        foreach($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if(method_exists($user, $method)) {
                $user->{$method}($value);
            }
        }

        $errors = $this->validator->validate($user);
        if($errors){
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        return $user;
    }
}