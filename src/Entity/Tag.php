<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libellé existe déjà"
 * )
 * @ApiResource(
 *      routePrefix="admin/",
 *      attributes={
 *          "security"="is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas accès aux tags"
 *      },
 *      normalizationContext={"groups"={"tag_read"}},
 *      collectionOperations={
 *          "get_tags"={
 *              "method"="GET",
 *          },
 *          "add_tags"={
 *              "method"="POST",
 *          }
 *      },
 *      itemOperations={
 *          "get_tag"={
 *              "method"="GET",
 *          },
 *          "update_tag"={
 *              "method"="PUT",
 *          }
 *      }
 * )
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grptag_read", "tag_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"grptag_read", "tag_read"})
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tags")
     * @Groups({"tag_read"})
     */
    private $groupeTags;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
        }

        return $this;
    }
}
