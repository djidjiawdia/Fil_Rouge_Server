<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libelle existe déjà"
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 * @ApiResource(
 *      attributes = {
 *          "pagination_items_per_page"=2,
 *          "pagination_client_items_per_page"=true,
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" =  "Vous n'avez pas droit à cette ressource"
 *      },
 *      normalizationContext = {"groups"={"profil_read"}},
 *      subresourceOperations = {
 *          "users_get_subresource" = {
 *              "method" = "GET",
 *              "path" = "/admin/profils/{id}/users",
 *              "normalization_context" = {"groups"={"profil_read_user"}}
 *          }
 *      },
 *      collectionOperations = {
 *          "get_profils" = {
 *              "method" = "GET",
 *              "path" = "/admin/profils"
 *          },
 *          "create_profils" = {
 *              "method" = "POST",
 *              "path" = "/admin/profils"
 *          }
 *      },
 *      itemOperations = {
 *          "get_profil" = {
 *              "method" = "GET",
 *              "path" = "/admin/profils/{id}"
 *          },
 *          "update_profil" = {
 *              "method" = "PUT",
 *              "path" = "/admin/profils/{id}"
 *          }
 *      }
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libellé ne doit pas être vide!")
     * @Groups({"profil_read", "user_read_all"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource
     * @Groups({"profil_read_user"})
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"profil_read_user"})
     */
    private $isDeleted;

    public function __construct()
    {
        $this->isDeleted = false;
        $this->users = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
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
