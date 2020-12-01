<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Service\UserService;
use App\Repository\FormateurRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    static $att_name = "formateur";

    private $uploadSer;
    private $userService;
    private $em;

    public function __construct(
        UserService $userService,
        UploadService $uploadSer,
        EntityManagerInterface $em
    ) {
        $this->userService = $userService;
        $this->uploadSer = $uploadSer;
        $this->em = $em;
    }

    /**
     * @Route(
     *      path="/api/formateurs", 
     *      name="create_formateur",
     *      methods="POST",
     *      defaults={
     *          "_controller"="\App\FormateurController::createFormateur",
     *          "_api_ressource_class"=Formateur::class,
     *          "_api_collection_operation_name"="create_formateur"
     *      }
     * )
     */
    public function createFormateur(Request $req): Response
    {
        $user = $this->userService->createUser($req, self::$att_name, Formateur::class);
        // dd($user);
        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user, Response::HTTP_CREATED, [], ["groups" => "user_read"]);
    }

    /**
     * @Route(
     *      name="update_formateur",
     *      path="/api/formateurs/{id}",
     *      methods="PUT",
     *      defaults={
     *          "_controller"="\App\FormateurController::updateFormateur",
     *          "_api_resource_class"=Formateur::class,
     *          "_api_item_operation_name"="update_formateur"
     *      }
     * )
     */
    public function updateFormateur(Request $req, int $id, FormateurRepository $formateurRepo): Response
    {
        $user = $formateurRepo->find($id);
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
