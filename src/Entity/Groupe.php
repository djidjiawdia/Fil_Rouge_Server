<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *      routePrefix="admin/",
 *      attributes={
 *          "security"="is_granted('ROLE_FORMATEUR')",
 *          "security_message"= "Vous n'avez pas acces à cette ressource"
 *      },
 *      normalizationContext={"groups"={"groupe_read"}},
 *      denormalizationContext={"groups"={"groupe_write"}},
 *      collectionOperations={
 *          "GET",
 *          "get_apprenants"={
 *              "method"="GET",
 *              "path"="/groupes/apprenants",
 *              "normalization_context"={"groups"={"groupe_app_read"}}
 *          },
 *          "POST"={
 *              "method"="POST",
 *              "path"="/groupes"
 *          }
 *      },
 *      itemOperations={
 *          "GET",
 *          "get_apprenants_by_id"={
 *              "method"="GET",
 *              "path"="/groupes/{id}/apprenants",
 *              "normalization_context"={"groups"={"groupe_app_read"}}
 *          },
 *          "PUT"
 *      }
 * )
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promo_write", "promo_read", "groupe_write", "promo_apprenant_attente", "groupe_read", "groupe_app_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne doit pas être vide")
     * @Groups({"promo_write", "promo_read", "groupe_read", "groupe_write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"groupe_read", "groupe_write"})
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le type ne doit pas être vide")
     * @Groups({"promo_read", "groupe_read", "promo_principal_read", "promo_apprenant_attente"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     * @Groups({"groupe_read", "groupe_write"})
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @Groups({"groupe_read", "groupe_write", "promo_write"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes", cascade={"persist"})
     * @Groups({"promo_write", "promo_read", "promo_principal_read", "promo_apprenant_attente", "groupe_read", "groupe_app_read", "groupe_write"})
     */
    private $apprenants;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->dateCreation = new DateTime();
        $this->isDeleted = false;
        $this->type = "secondaire";
        $this->statut = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

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
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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
}
