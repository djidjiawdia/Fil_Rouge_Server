<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table("users")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"admin"="User", "formateur"="Formateur", "apprenant"="Apprenant", "cm"="CommunityManager"})
 * @UniqueEntity(
 *      fields={"email"},
 *      message="L'email existe déjà"
 * )
 * @ApiResource(
 *      routePrefix="/admin/users",
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez accès à cette ressource"
 *      },
 *      normalizationContext={"groups"={"user_read"}},
 *      denormalizationContext={"groups"={"user_write"}},
 *      collectionOperations={
 *           "get_users"={
 *               "method"="GET",
 *               "path"="/"
 *           },
 *          "create_user"={
 *              "method"="POST",
 *              "deserialize"=false
 *          }
 *      },
 *      itemOperations={
 *          "get_user"={
 *              "method"="GET",
 *              "path"="/{id}",
 *              "normalization_context"={"groups"={"user_read", "user_read_all"}}
 *          },
 *          "update_user"={
 *              "method"="PUT",
 *              "path"="/{id}",
 *              "deserialize"=false
 *          },
 *          "delete_user"={
 *              "method"="DELETE",
 *              "path"="/{id}"
 *          }
 *      }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *      "user_read",
     *      "user_write",
     *      "profil_read_user",
     *      "promo_write",
     *      "groupe_write"
     * })
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="L'email ne doit pas être vide.")
     * @Assert\Email(message = "L'email '{{ value }}' n'est pas valid.")
     * @Groups({
     *      "user_read",
     *      "user_write",
     *      "promo_write",
     *      "groupe_write"
     * })
     */
    protected $email;

    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({
     *      "user_read",
     *      "user_write",
     *      "profil_read_user",
     *      "promo_read",
     *      "promo_principal_read",
     *      "groupe_read",
     *      "groupe_app_read"
     * })
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({
     *      "user_read",
     *      "user_write",
     *      "profil_read_user",
     *      "promo_read",
     *      "promo_principal_read",
     *      "groupe_read",
     *      "groupe_app_read"
     * })
     */
    protected $nom;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"user_read", "user_write"})
     */
    protected $avatar;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user_read"})
     */
    protected $statut;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user_read"})
     */
    protected $isDeleted;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({
     *      "user_read",
     *      "user_write",
     *      "promo_read",
     *      "promo_principal_read"
     * })
     */
    protected $profil;

    public function __construct()
    {
        $this->statut = false;
        $this->isDeleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.strtoupper($this->getProfil()->getLibelle());

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

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

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }
}
