<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get_apprenants"={
 *              "method"="GET",
 *              "path"="/apprenants",
 *              "normalization_context"={"groups"={"user_read"}},
 *              "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "create_apprenant"={
 *              "method"="POST",
 *              "deserialize"=false,
 *              "security"="is_granted('ROLE_FORMATEUR')",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          }
 *      },
 *      itemOperations={
 *          "get_apprenant"={
 *              "method"="GET",
 *              "path"="/apprenants/{id}",
 *              "normalization_context"={"groups"={"user_read", "user_read_all"}},
 *              "security"="is_granted('APP_VIEW', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "update_apprenant"={
 *              "method"="PUT",
 *              "path"="/apprenants/{id}",
 *              "security"="is_granted('APP_EDIT', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *      }
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     */
    private $profilSortie;

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }
}
