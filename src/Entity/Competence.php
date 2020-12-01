<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libelle est déjà utilisé"
 * )
 * @ApiResource(
 *      routePrefix="/admin",
 *      collectionOperations={
 *          "get_competences"={
 *              "method"="GET",
 *              "security"="is_granted('COMPETENCE_VIEW', object)",
 *              "security_message"="Vous n'avez pas accès à cette ressource."
 *          },
 *          "create_competence"={
 *              "method"="POST",
 *              "deserialize"=false
 *          }
 *      },
 *      itemOperations={
 *          "get_competence"={
 *              "method"="GET",
 *          },
 *          "update_competence"={
 *              "method"="PUT",
 *          }
 *      },
 * )
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libellé ne doit pas être vide."))
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence", cascade={"persist"})
     * @Assert\Valid
     * @Assert\Count(
     *      min=3,
     *      max=3,
     *      exactMessage="Vous devrez avoir exactement {{ limit }} niveaux"
     * )
     */
    private $niveaux;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, mappedBy="competences", cascade={"persist"})
     * @Assert\Count(
     *      min=1,
     *      minMessage="Affecter au moins un groupe de competences"
     * )
     */
    private $groupeCompetences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
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

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

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
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->removeElement($groupeCompetence)) {
            $groupeCompetence->removeCompetence($this);
        }

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
