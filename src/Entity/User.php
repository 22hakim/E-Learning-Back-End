<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['read_collections']],
        ],
        'post'
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['read_item']],
        ],
        'put',
        'patch',
        'delete'
    ],
)]
#[ApiFilter(RangeFilter::class, properties: ['age'])]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["read_collections", "read_item"])]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(["read_collections", "read_item"])]
    private $password;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    #[Groups(["read_item"])]
    private $User_Articles;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["read_collections", "read_item"])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(["read_collections", "read_item"])]
    private $UpdatedAt;

    #[ORM\Column(type: 'boolean')]
    private $status;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read_collections", "read_item"])]
    private $age;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->User_Articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function getUserIdentifier(): string
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {

        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getUserArticles(): Collection
    {
        return $this->User_Articles;
    }

    
    public function addUserArticle(Article $userArticle): self
    {
        if (!$this->User_Articles->contains($userArticle)) {
            $this->User_Articles[] = $userArticle;
            $userArticle->setAuthor($this);
        }

        return $this;
    }

    public function removeUserArticle(Article $userArticle): self
    {
        if ($this->User_Articles->removeElement($userArticle)) {
            // set the owning side to null (unless already changed)
            if ($userArticle->getAuthor() === $this) {
                $userArticle->setAuthor(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }
}
