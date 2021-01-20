<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @UniqueEntity(
 *      fields={"titre"},
 *      message="Le titre est déjà utilisé"
 * )
 * @ApiResource( 
 *      routePrefix="admin/",
 *      attributes={"pagination_enabled"=false},
 *      normalizationContext={"groups"={"promo_read"}},
 *      denormalizationContext={"groups"={"promo_write"}},
 *      collectionOperations={
 *         "get_promos"={
 *              "security"="(is_granted('ROLE_FORMATEUR','ROLE_CM'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "path"="/promos",
 *              "method"="GET"
 *          },
 *          "get_promos_principal"={
 *              "security"="(is_granted('ROLE_FORMATEUR','ROLE_CM'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"promo_principal_read"}},
 *              "method"="GET", 
 *              "path"="/promos/principal"
 *          },
 *          "get_promos_apprenant"={
 *              "security"="(is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "method"="GET", 
 *              "path"="/promos/apprenants/attente"
 *          },
 *           "add_promo"={
 *              "method"="POST",
 *              "deserialize"=false
 *          },
 * 
 *      },
 *      itemOperations={
 *          "get_promo"={
 *              "security"="(is_granted('ROLE_FORMATEUR','ROLE_CM'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "method"="GET"
 *          },
 *          "get_promo_principal"={
 *              "security"="(is_granted('ROLE_FORMATEUR','ROLE_CM'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"promo_principal_read"}},
 *              "method"="GET", 
 *              "path"="/promos/{id}/principal"
 *          },
 *           "get_promo_referentiel"={
 *              "security"="(is_granted('ROLE_FORMATEUR','ROLE_CM'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"promo_referentiel_read"}},
 *              "method"="GET",
 *              "path"="/promos/{id}/referentiels"
 *          },
 *          "get_promo_apprenant"={
 *              "security"="(is_granted('ROLE_FORMATEUR','ROLE_CM'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "method"="GET", 
 *              "path"="/promos/{id}/apprenants/attente"
 *          },
 *          "get_promo_groupe"={
 *              "security"="(is_granted('ROLE_FORMATEUR','ROLE_CM'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"promo_apprenant_read"}},
 *              "method"="GET", 
 *              "path"="/promos/{id}/groupes/{ida}/apprenants"
 *          },
 *          "get_promo_formateur"={
 *              "security"="(is_granted('ROLE_FORMATEUR','ROLE_CM'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"promo_formateur_read"}},
 *              "method"="GET", 
 *              "path"="/promos/{id}/formateurs"
 *          },
 *          "update_promo"={
 *              "security"="(is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "method"="PUT"
 *          },
 *          "update_promo_apprenant"={
 *              "security"="(is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "method"="PUT", 
 *              "path"="/promos/{id}/apprenants"
 *          },
 *          "update_promo_formateur"={
 *              "security"="(is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "method"="PUT", 
 *              "path"="/promos/{id}/formateurs"
 *          },
 *           "ajouter_promo_groupe"={
 *              "security"="(is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "method"="PUT", 
 *              "path"="/promos/{id}/groupes",
 *          },
 *           "update_promo_groupe"={
 *              "security"="(is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "method"="PUT", 
 *              "path"="/promos/{id}/groupes/{idgrpe}",
 *          },
 *      }
 * )
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *      "ref_write",
     *      "promo_write",
     *      "promo_read",
     *      "promo_principal_read",
     *      "groupe_write"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La langue ne doit pas être vide")
     * @Groups({"promo_write", "promo_read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre ne doit pas être vide")
     * @Groups({"promo_write", "promo_read", "promo_principal_read", "groupe_read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description ne doit pas être vide")
     * @Groups({"promo_write", "promo_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieu;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="La date de début ne doit pas être vide")
     * @Groups({"promo_write", "promo_read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="La date de début ne doit pas être vide")
     * @Groups({"promo_write", "promo_read"})
     */
    private $dateProvisoire;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_read"})
     */
    private $fabrique;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos")
     * @Assert\NotNull(message="Le referentiel est obligatoire")
     * @Groups({"promo_write", "promo_read", "promo_principal_read"})
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos")
     * @Assert\Valid
     * @Assert\Count(
     *      min=1,
     *      minMessage="Affecter au moins un formateur"
     * )
     * @Groups({"promo_write", "promo_read", "promo_principal_read"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo", cascade={"persist"})
     * @Assert\Valid
     * @Assert\Count(
     *      min=1,
     *      minMessage="Ajouter le groupe principal"
     * )
     * @Groups({"promo_write", "promo_read", "promo_principal_read"})
     */
    private $groupes;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"promo_read", "promo_write"})
     */
    private $avatar;

    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->isDeleted = false;
        $this->fabrique = "Sonatel Academy";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateProvisoire(): ?\DateTimeInterface
    {
        return $this->dateProvisoire;
    }

    public function setDateProvisoire(\DateTimeInterface $dateProvisoire): self
    {
        $this->dateProvisoire = $dateProvisoire;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

        return $this;
    }

    public function getAvatar()
    {
        if($this->avatar != null){
            return \base64_encode(stream_get_contents($this->avatar));
        }
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
