<?php

namespace App\Controller;

use App\Entity\Apprenant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApprenantController extends AbstractController
{
    static $att_name = "apprenant";
    
    /**
     * @Route(
     *      path="/apprenants", 
     *      name="create_apprenant",
     *      methods="POST",
     *      defaults={
     *          "_controller"="\App\ApprenantController::createApprenant",
     *          "_api_ressource_class"=Apprenant::class,
     *          "_api_collection_operation_name"="create_apprenant"
     *      }
     * )
     */
    public function createApprenant(Request $req): Response
    {
        $user = $this->userService->createUser($req, self::$att_name, Apprenant::class);
        // dd($user);
        $this->em->persist($user);
        $this->em->flush();
        
        return $this->json($user, Response::HTTP_CREATED, [], ["groups" => "user_read"]);
    }
}
