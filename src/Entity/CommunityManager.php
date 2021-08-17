<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommunityManagerRepository;

/**
 * @ORM\Entity(repositoryClass=CommunityManagerRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get_cmangers"={
 *              "method"="GET",
 *              "path"="/cmangers",
 *              "normalization_context"={"groups"={"user_read"}},
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *      },
 *      itemOperations={
 *          "get_cmanager"={
 *              "method"="GET",
 *              "path"="/cmangers/{id}",
 *              "normalization_context"={"groups"={"user_read", "user_read_all"}},
 *              "security"="is_granted('CM_VIEW', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "update_cmanager"={
 *              "method"="PUT",
 *              "path"="/cmangers/{id}",
 *              "deserialize"=false,
 *              "security"="is_granted('CM_EDIT', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *      }
 * )
 */
class CommunityManager extends User
{

}
