<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libellé existe déjà"
 * )
 * @ApiResource(
 *      routePrefix="/admin",
 *      attributes={
 *          "security"="is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas accès aux tags"
 *      },
 *      normalizationContext={"groups"={"grptag_read"}},
 *      collectionOperations={
 *          "get_grptags"={
 *              "method"="GET",
 *              "path"="/grptags"
 *          },
 *          "add_grptag"={
 *              "method"="POST",
 *              "path"="grptags",
 *              "deserialize"=false
 *          }
 *      },
 *      itemOperations={
 *          "get_grptag"={
 *              "method"="GET",
 *              "path"="/grptags/{id}"
 *          },
 *          "update_grptag"={
 *              "method"="PUT",
 *              "path"="grptags/{id}"
 *          }
 *      }
 * )
 */
class GroupeTag
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
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags", cascade={"persist"})
     * @Groups({"grptag_read"})
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

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
