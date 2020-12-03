<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\GroupeTag;
use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class GroupeTagController extends AbstractController
{
    // private $denormalizer;
    // private $serializer;
    // private $validator;
    // private $em;
    // public function __construct(
    //     DenormalizerInterface $denormalizer,
    //     SerializerInterface $serializer,
    //     ValidatorInterface $validator,
    //     EntityManagerInterface $em
    // )
    // {
    //     $this->denormalizer = $denormalizer;
    //     $this->serializer = $serializer;
    //     $this->validator = $validator;
    //     $this->em = $em;
    // }
    // /**
    //  * @Route(
    //  *      name="create_grptag",
    //  *      path="api/admin/grptags",
    //  *      methods="POST",
    //  *      defaults={
    //  *          "_controller"="\app\GroupeTagController::createGrpTag",
    // *           "_api_resource_class"=GroupeTag::class,
    // *           "_api_collection_operation_name"="add_grptag"
    //  *      }
    //  * )
    //  */
    // public function createGrpTag(Request $req){
    //     $grptagsTab = json_decode($req->getContent(), true);
    //     // dd($grptagsTab);
        
    //     $tags = $this->denormalizer->denormalize($grptagsTab["tags"], "App\Entity\Tag[]");
    //     // dd($tags);

    //     $groupetag = new GroupeTag();

    //     $groupetag->setLibelle($grptagsTab['libelle']);
    //     foreach($tags as $tag){
    //         $groupetag->addTag($tag);
    //     }

    //     $errors = $this->validator->validate($groupetag);
    //     if ($errors){
    //         $errors = $this->serializer->serialize($errors,"json");
    //         return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
    //     }

    //     $this->em->persist($groupetag);
    //     $this->em->flush();

    //     return $this->json($groupetag, Response::HTTP_CREATED);
    // }

    // /**
    //  * @Route(
    //  *      name="update_grptag",
    //  *      path="api/admin/grptags/{id}",
    //  *      methods="PUT",
    //  *      defaults={
    //  *          "_controller"="\app\GroupeTagController::updateGrpTag",
    // *           "_api_resource_class"=GroupeTag::class,
    // *           "_api_item_operation_name"="update_grptag"
    //  *      }
    //  * )
    //  */
    // public function updateGrpTag(Request $req, int $id, GroupeTagRepository $repoGrpTag){
    //     $grptag = $repoGrpTag->find($id);
    //     // dd($grptag);
    //     $grcTab = json_decode($req->getContent(), true);
        
    //     if(!empty($grcTab["updateTags"])){
    //         foreach($grcTab["updateTags"] as $tags){
    //             if(!empty($tags) && isset($tags["id"]) && isset($tags["libelle"]) && isset($tags["descriptif"])){
    //                 foreach($grptag->getTags() as $k => $tg){
    //                     if($tg->getId() == $tags["id"]){
    //                         $grptag->getTags()[$k]->setLibelle($tags["libelle"]);
    //                         $grptag->getTags()[$k]->setDescriptif($tags["descriptif"]);
    //                     }
    //                 }
    //             }elseif(!empty($tags) && !isset($tags["id"]) && isset($tags["libelle"]) && isset($tags["descriptif"])){
    //                 $tag = $this->denormalizer->denormalize($tags, "App\Entity\Tag");
    //                 // dd($tag);
    //                 $errors = $this->validator->validate($tag);
    //                 if ($errors){
    //                     $errors = $this->serializer->serialize($errors,"json");
    //                     return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
    //                 }
    //                 $grptag->addTag($tag);
    //             }elseif(!empty($tags) && isset($tags["id"]) && !isset($tags["libelle"]) && !isset($tags["descriptif"])){
    //                 foreach($grptag->getTags() as $tg){
    //                     if($tg->getId() == $tags["id"]){
    //                         $grptag->removeTag($tg);
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     $errors = $this->validator->validate($grptag);
    //     if ($errors){
    //         $errors = $this->serializer->serialize($errors,"json");
    //         return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
    //     }
        
    //     $this->em->flush();

    //     return $this->json($grptag, Response::HTTP_OK);
        
    // }
}
