<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommunityRepository")
 *
 * @UniqueEntity(fields={"name"}, message="cette association existe deja")
 */
class Community
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Le nom de la community doit être renseigné")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdCommunities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="communities")
     * @ORM\OrderBy({"lastName": "ASC", "firstName": "ASC"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="community")
     */
    private $posts;




    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Image(mimeTypesMessage="Le fichier doit etre une image",
     *     maxSize="1M",
     *     maxSizeMessage="L'image ne doit pas depasser 1 Mo")
     */
    private $image;

    public function __toString()
    {
        return $this->getName();
    }


    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->users = new ArrayCollection();
        $this->posts = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
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
            $post->setCommunity($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getCommunity() === $this) {
                $post->setCommunity(null);
            }
        }

        return $this;
    }




    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

}
