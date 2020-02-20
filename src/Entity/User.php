<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity(fields={"email"}, message="Un utilisateur existe deja avec cet email")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     *
     *@Assert\NotBlank(message="Le prénom est obligatoire")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Le nom sest obligatoire")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Email(message="L'email n'est pas valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank(message="Lemot de passe est obligatoire")
     * @Assert\Regex(pattern="/^(?=.*[0-9])(?=.*[A-Z]).{6,20}$/",
     *      message="Mot de passe invalide")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role = 'ROLE_USER';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Community", mappedBy="owner", cascade={"remove"})
     */
    private $createdCommunities;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Community", mappedBy="users", cascade={"remove"})
     * @ORM\OrderBy{"creationDate" : "DESC"}
     */
    private $communities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user", cascade={"remove"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Image(mimeTypesMessage="Le fichier doit etre une image",
     *     maxSize="1M",
     *     maxSizeMessage="L'image ne doit pas depasser 1 Mo")
     */
    private $avatar;

    public function __construct()
    {
        $this->createdCommunities = new ArrayCollection();
        $this->communities = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): User
    {

        $this->plainPassword = $plainPassword;
        return $this;
    }



    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Community[]
     */
    public function getCreatedCommunities(): Collection
    {
        return $this->createdCommunities;
    }

    public function addCreatedCommunity(Community $createdCommunity): self
    {
        if (!$this->createdCommunities->contains($createdCommunity)) {
            $this->createdCommunities[] = $createdCommunity;
            $createdCommunity->setOwner($this);
        }

        return $this;
    }

    public function removeCreatedCommunity(Community $createdCommunity): self
    {
        if ($this->createdCommunities->contains($createdCommunity)) {
            $this->createdCommunities->removeElement($createdCommunity);
            // set the owning side to null (unless already changed)
            if ($createdCommunity->getOwner() === $this) {
                $createdCommunity->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Community[]
     */
    public function getCommunities(): Collection
    {
        return $this->communities;
    }

    public function hasCommunity(Community $community)
    {
        return $this->getCommunities()->contains($community);
    }

    public function addCommunity(Community $community): self
    {
        if (!$this->communities->contains($community)) {
            $this->communities[] = $community;
            $community->addUser($this);
        }

        return $this;
    }

    public function removeCommunity(Community $community): self
    {
        if ($this->communities->contains($community)) {
            $this->communities->removeElement($community);
            $community->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize(
       [
           $this->id,
           $this->firstName,
           $this->lastName,
           $this->email,
           $this->password,
           $this->role
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->password,
            $this->role
            ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
