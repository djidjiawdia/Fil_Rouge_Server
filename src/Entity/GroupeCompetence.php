<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libellé existe déjà"
 * )
 * @ApiResource(
 *      routePrefix="/admin",
 *      normalizationContext={"groups"={"grpe_comp_read"}},
 *      denormalizationContext={"groups"={"grpe_comp_write"}},
 *      subresourceOperations={
 *          "competences_get_subresource"={
 *              "path"="admin/grpecompetence/{id}/competences"
 *          }
 *      },
 *      collectionOperations={
 *          "get_grpecompetences"={
 *              "method"="GET",
 *              "path"="/grpecompetences"
 *          },
 *          "get_grpecompetences_competences"={
 *              "method"="GET",
 *              "path"="/grpecompetences/competences",
 *              "security"="is_granted('VIEW_GRPECOMPETENCE', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "create_grpecompetence"={
 *              "method"="POST",
 *              "path"="/grpecompetences"
 *          }
 *      },
 *      itemOperations={
 *          "get_grpecompetence"={
 *              "method"="GET",
 *              "path"="/grpecompetences/{id}",
 *              "security"="is_granted('VIEW_GRPECOMPETENCE', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "update_grpecompetence"={
 *              "method"="PUT",
 *              "path"="/grpecompetences/{id}",
 *              "security"="is_granted('EDIT_GRPECOMPETENCE', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          }
 *      }
 * )
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpe_comp_write", "comp_write", "ref_write", "ref_grp_comp"})
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libellé ne doit pas être vide.")
     * @Groups({"grpe_comp_read", "grpe_comp_write", "ref_grp_comp"})
     */
    private $libelle;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le descriptif ne doit pas être vide.")
     * @Groups({"grpe_comp_read", "grpe_comp_write", "ref_grp_comp"})
     */
    private $descriptif;
    
    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences", cascade={"persist"})
     * @JoinColumn(name="competence_id", nullable=true)
     * @Assert\Valid
     * @Assert\Count(
     *      min=1,
     *      minMessage="Affecter au moins une competence"
     * )
     * @ApiSubresource
     * @Groups({"grpe_comp_read", "grpe_comp_write", "ref_grp_comp"})
     */
    private $competences;
    
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"grpe_comp_write"})
     */
    private $isDeleted;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupeCompetences")
     */
    private $referentiels;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->isDeleted = false;
        $this->referentiels = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competences->removeElement($competence);

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
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGroupeCompetence($this);
        }

        return $this;
    }
}
