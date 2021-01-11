<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libelle est déjà utilisé"
 * )
 * @ApiResource(
 *      routePrefix="admin",
 *      attributes={"pagination_enabled"=false},
 *      normalizationContext={"groups"={"ref_read"}},
 *      denormalizationContext={"groups"={"ref_write"}},
 *      subresourceOperations={
 *          "groupe_competences_competences_get_subresource"={
 *              "path"="admin/referentiels/{id}/grpecompetences/{groupeCompetences}/competences",
 *              "security"="is_granted('REF_VIEW', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          }
 *      },
 *      collectionOperations={
 *          "get_referentiels"={
 *              "method"="GET",
 *              "security"="is_granted('REF_VIEW', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "get_referentiels_grpcompetences"={
 *              "method"="GET",
 *              "path"="/referentiels/grpecompetences",
 *              "normalization_context"={"groups"={"ref_grp_comp"}},
 *              "security"="is_granted('REF_VIEW', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "add_referentiel"={
 *              "method"="POST",
 *              "security"="is_granted('REF_CREATE', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          }
 *      },
 *      itemOperations={
 *          "get_referentiel"={
 *              "method"="GET",
 *              "security"="is_granted('REF_VIEW', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "update_referentiel"={
 *              "method"="PUT",
 *              "security"="is_granted('REF_EDIT', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          }
 *      }
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *      "ref_write",
     *      "ref_read",
     *      "promo_write",
     *      "promo_read",
     *      "promo_principal_read"
     * })
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libellé ne doit pas être vide")
     * @Groups({
     *      "ref_write",
     *      "ref_read",
     *      "promo_read",
     *      "promo_principal_read"
     * })
     */
    private $libelle;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La presentation ne doit pas être vide")
     * @Groups({"ref_write", "ref_read", "promo_read", "promo_principal_read"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"ref_write", "ref_read"})
     */
    private $programme;

    /**
     * @ORM\Column(type="text")
     * @Groups({"ref_write", "ref_read"})
     */
    private $critereEvaluation;
    
    /**
     * @ORM\Column(type="text")
     * @Groups({"ref_write", "ref_read"})
     */
    private $critereAdmission;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     * @Assert\Valid
     * @ApiSubresource()
     * @Groups({"ref_write", "ref_read", "ref_grp_comp"})
     */
    private $groupeCompetences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     */
    private $promos;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme()
    {
        if($this->programme != null){
            return \base64_encode(stream_get_contents($this->programme));
        }
        return null;
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        $this->groupeCompetences->removeElement($groupeCompetence);

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
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }
}
