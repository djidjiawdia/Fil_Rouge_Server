<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libellé existe déjà"
 * )
 * @ApiResource(
 *      routePrefix="/admin",
 *      normalizationContext={"groups"={"groupecompetence_read"}},
 *      collectionOperations={
 *          "get_grpecompetences"={
 *              "method"="GET",
 *              "path"="/grpecompetences"
 *          },
 *          "get_grpecompetences_competences"={
 *              "method"="GET",
 *              "path"="grpecompetences/competences",
 *              "security"="is_granted('VIEW_GRPECOMPETENCE', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "create_grpecompetence"={
 *              "method"="POST",
 *              "path"="grpecompetences",
 *              "deserialize"=false
 *          }
 *      },
 *      itemOperations={
 *          "get_grpecompetence"={
 *              "method"="GET",
 *              "path"="/grpecompetences/{id}",
 *              "security"="is_granted('VIEW_GRPECOMPETENCE', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource"
 *          },
 *          "get_grpecompetence_competences"={
 *              "method"="GET",
 *              "path"="/grpecompetence/{id}/competences"
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
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupecompetence_read"})
     * @Assert\NotBlank(message="Le libellé ne doit pas être vide.")
     */
    private $libelle;
    
    /**
     * @ORM\Column(type="text")
     * @Groups({"groupecompetence_read"})
     * @Assert\NotBlank(message="Le descriptif ne doit pas être vide.")
     */
    private $descriptif;
    
    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences", cascade={"persist"})
     * @Assert\Valid
     * @Assert\Count(
     *      min=1,
     *      minMessage="Affecter au moins une competence"
     * )
     */
    private $competences;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
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
}
