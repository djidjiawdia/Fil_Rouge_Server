<?php

namespace App\Controller;

use App\Entity\CommunityManager;
use App\Repository\CommunityManagerRepository;
use App\Service\UploadService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommunityManagerController extends AbstractController
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
     *      path="/api/cmangers/{id}", 
     *      name="update_cmanager",
     *      methods="PUT",
     *      defaults={
     *          "_controller"="\App\ApprenantController::updateCManager",
     *          "_api_ressource_class"=Apprenant::class,
     *          "_api_item_operation_name"="update_cmanager"
     *      }
     * )
     */
    public function updateCManager(Request $req, int $id, CommunityManagerRepository $repo): Response
    {
        $cmanager = $repo->find($id);
        if($cmanager && !$cmanager->getIsDeleted()) {
            $userTab = $this->uploadSer->getContentFromRequest($req, "avatar");
            // dd($userTab);
            $cmanager = $this->userService->updateUser($cmanager, $userTab);
            // dd($user);

            $this->em->flush();
        }
        
        return $this->json($cmanager, Response::HTTP_OK, [], ["groups" => "user_read"]);
    }
}
