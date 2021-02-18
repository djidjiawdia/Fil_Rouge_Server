<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"statut"})
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

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants")
     */
    private $groupes;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user_read", "promo_apprenant_attente"})
     */
    private $statut;

    public function __construct()
    {
        parent::__construct();
        $this->statut = false;
        $this->groupes = new ArrayCollection();
    }

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
