<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use App\Service\UploadService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    private $em;
    private $uploadSer;
    private $userService;

    public function __construct(
        EntityManagerInterface $em,
        UploadService $uploadSer,
        UserService $userService
    )
    {
        $this->em = $em;
        $this->uploadSer = $uploadSer;
        $this->userService = $userService;
    }

    /**
     * @Route(
     *      name="create_user",
     *      path="/api/admin/users",
     *      methods="POST",
     *      defaults={
     *          "_controller"="\App\UserController::createUser",
     *          "_api_resource_class"=User::class,
     *          "_api_collection_operation_name"="create_user"
     *      }
     * )
     */
    public function createUser(Request $req) {
        $user = $this->userService->createUser($req);
        $this->em->persist($user);
        $this->em->flush();
        
        return $this->json($user, Response::HTTP_CREATED, [], ["groups" => ["user_read"]]);
    }

    /**
     * @Route(
     *      name="update_user",
     *      path="/api/admin/users/{id}",
     *      methods="PUT",
     *      defaults={
     *          "_controller"="\App\UserController::updateUser",
     *          "_api_resource_class"=User::class,
     *          "_api_item_operation_name"="update_user"
     *      }
     * )
     */
    public function updateUser(Request $req, int $id, UserRepository $userRepo): Response
    {
        $user = $userRepo->find($id);
        if($user && !$user->getIsDeleted()) {
            $userTab = $this->uploadSer->getContentFromRequest($req, "avatar");
            // dd($userTab);
            $user = $this->userService->updateUser($user, $userTab);
            // dd($user);

            $this->em->flush();
        }
        
        return $this->json($user, Response::HTTP_OK, [], ["groups" => "user_read"]);
    }
}