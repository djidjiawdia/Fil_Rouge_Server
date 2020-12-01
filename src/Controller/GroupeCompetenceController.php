<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class GroupeCompetenceController extends AbstractController
{
    private $denormalizer;
    private $em;
    private $validator;
    private $serializer;
    private $normalizer;

    public function __construct(
        DenormalizerInterface $denormalizer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        NormalizerInterface $normalizer
    )
    {
        $this->denormalizer = $denormalizer;
        $this->em = $em;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
    }

    /**
    * @Route(
    *      name="create_grpecompetence",
    *      path="/api/admin/grpecompetences",
    *      methods="POST",
    *      defaults={
    *          "_controller"="\app\GroupeCompetenceController::createGrpCompetence",
    *           "_api_resource_class"=GroupeCompetence::class,
    *           "_api_collection_operation_name"="create_grpecompetence"
    *      }
    * )
    */
    public function createGrpCompetence(Request $req){
        $grpeCompetence = new GroupeCompetence;

        if(!$this->isGranted('CREATE_GRPECOMPETENCE', $grpeCompetence)){
            return $this->json([
                "message" => "Vous n'avez pas accès à cette ressource"
            ], Response::HTTP_FORBIDDEN);
        }

        $grc = json_decode($req->getContent(), true);
        // dd($grc);
        
        if(!empty($grc["competences"])){
            $competences = $this->denormalizer->denormalize($grc["competences"], "App\Entity\Competence[]");
            // dd($competences);
            foreach($competences as $cmpt){
                $grpeCompetence->addCompetence($cmpt);
            }
            // dd($grpeCompetence);
        }
        
        $grpeCompetence
            ->setLibelle($grc["libelle"])
            ->setDescriptif($grc["descriptif"]);

        // dd($grpeCompetence);

        $errors = $this->validator->validate($grpeCompetence);
        if ($errors){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }

        $this->em->persist($grpeCompetence);
        $this->em->flush();

        return $this->json($grpeCompetence, Response::HTTP_CREATED);
    }
}
