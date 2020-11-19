<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get_formateurs"={
 *              "method"="GET",
 *              "path"="/formateurs",
 *              "normalization_context"={"groups"={"user_read"}},
 *              "security"="is_granted('ROLE_CM')",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          }
 *      },
 *      itemOperations={
 *          "get_apprenant"={
 *              "method"="GET",
 *              "path"="/formateurs/{id}",
 *              "normalization_context"={"groups"={"user_read", "user_read_all"}},
 *              "security"="is_granted('FORMATEUR_VIEW', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "update_formateur"={
 *              "method"="PUT",
 *              "path"="/formateurs/{id}",
 *              "security"="is_granted('FORMATEUR_EDIT', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *      }
 * )
 */
class Formateur extends User
{
    
}
