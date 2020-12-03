<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CompetenceController extends AbstractController
{
    // private $denormalizer;
    // private $em;
    // private $validator;
    // private $serializer;
    
    // public function __construct(
    //     DenormalizerInterface $denormalizer,
    //     EntityManagerInterface $em,
    //     ValidatorInterface $validator,
    //     SerializerInterface $serializer
    // )
    // {
    //     $this->denormalizer = $denormalizer;
    //     $this->em = $em;
    //     $this->validator = $validator;
    //     $this->serializer = $serializer;
    // }

    // /**
    //  * @Route(
    //  *      name="create_competence",
    //  *      path="api/admin/competences",
    //  *      methods="POST",
    //  *      defaults={
    //  *          "_controller"="\app\CompetenceController::createCompetence",
    // *           "_api_resource_class"=Competence::class,
    // *           "_api_collection_operation_name"="create_competence"
    //  *      }
    //  * )
    //  */
    // public function createCompetence(Request $req, GroupeCompetenceRepository $repo){
    //     $competence = new Competence;
    //     $competenceTab = json_decode($req->getContent(), true);

    //     $niveaux = $this->denormalizer->denormalize($competenceTab["niveaux"], "App\Entity\Niveau[]");
    //     foreach($niveaux as $niveau){
    //         $competence->addNiveau($niveau);
    //     }

    //     $competence->setLibelle($competenceTab["libelle"]);

    //     if(!empty($competenceTab["groupeCompetences"])){
    //         foreach($competenceTab["groupeCompetences"] as $id){
    //             $grpcompetence = $repo->find($id);
    //             if($grpcompetence && !$grpcompetence->getIsDeleted()){
    //                 // dd($grpcompetence);
    //                 $competence->addGroupeCompetence($grpcompetence);
    //             }else{
    //                 return $this->json(["message" => "GroupeCompetence {$id}: Resource not found!"], Response::HTTP_BAD_REQUEST);
    //             }
    //             // $this->em->persist($grpcompetence);
    //         }
    //     }

    //     $errors = $this->validator->validate($competence);
    //     if ($errors){
    //         $errors = $this->serializer->serialize($errors,"json");
    //         return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
    //     }
        
    //     $this->em->persist($competence);
    //     $this->em->flush();

    //     return $this->json($competence, Response::HTTP_CREATED);
    // }

    
}
