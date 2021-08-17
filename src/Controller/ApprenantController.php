<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Repository\ApprenantRepository;
use App\Service\UploadService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApprenantController extends AbstractController
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
     *      path="/api/apprenants/{id}", 
     *      name="update_apprenant",
     *      methods="PUT",
     *      defaults={
     *          "_controller"="\App\ApprenantController::updateApprenant",
     *          "_api_ressource_class"=Apprenant::class,
     *          "_api_item_operation_name"="update_apprenant"
     *      }
     * )
     */
    public function updateApprenant(Request $req, int $id, ApprenantRepository $repo): Response
    {
        $apprenant = $repo->find($id);
        if($apprenant && !$apprenant->getIsDeleted()) {
            $userTab = $this->uploadSer->getContentFromRequest($req, "avatar");
            if($apprenant->getStatut()){
                $userTab["statut"] = false;
            }
            // dd($userTab);
            $apprenant = $this->userService->updateUser($apprenant, $userTab);
            // dd($user);

            $this->em->flush();
        }
        
        return $this->json($apprenant, Response::HTTP_OK, [], ["groups" => "user_read"]);
    }

    
}
