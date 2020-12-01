<?php

namespace App\Controller;

use App\Entity\CommunityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommunityManagerController extends AbstractController
{
    static $att_name = "cm";
    
    /**
     * @Route(
     *      path="/cmanagers", 
     *      name="create_cm",
     *      methods="POST",
     *      defaults={
     *          "_controller"="\App\CommunityManagerController::createCm",
     *          "_api_ressource_class"=CommunityManager::class,
     *          "_api_collection_operation_name"="create_cm"
     *      }
     * )
     */
    public function createCm(Request $req): Response
    {
        $user = $this->userService->createUser($req, self::$att_name, CommunityManager::class);
        // dd($user);
        $this->em->persist($user);
        $this->em->flush();
        
        return $this->json($user, Response::HTTP_CREATED, [], ["groups" => "user_read"]);
    }
}
