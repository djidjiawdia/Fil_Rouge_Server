<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libellé existe déjà"
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 * @ApiResource(
 *      routePrefix="/admin/profilsorties",
 *      attributes={
 *          "pagination_items_per_page"=2,
 *          "pagination_client_items_per_page"=true,
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez accès à cette ressource"
 *      },
 *      normalizationContext={"groups"={"profilsortie_read"}},
 *      collectionOperations={
 *          "get_profilsorties"={
 *              "method"="GET",
 *              "path"="/",
 *              "defaults"={"isDeleted"=true}
 *          },
 *          "create_profilsortie"={
 *              "method"="POST",
 *              "path"="",
 *          }
 *      },
 *      itemOperations={
 *          "get_profilsortie"={
 *              "method"="GET",
 *              "path"="/{id}"
 *          },
 *          "update_profilsortie"={
 *              "method"="PUT",
 *              "path"="/{id}"
 *          }
 *      }
 * )
 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profilsortie_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libellé ne doit pas être vide!")
     * @Groups({"profilsortie_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"profilsortie_read"})
     */
    private $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     */
    private $apprenants;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->isDeleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
            }
        }

        return $this;
    }
}
