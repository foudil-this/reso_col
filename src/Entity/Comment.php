<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\card", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\NotBlank()
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publicationDate;
    //__________________________________________________________________________________________________________________
    public function __construct()
    {
        $this->publicationDate=new \DateTime();


    }
    //__________________________________________________________________________________________________________________

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCard(): ?card
    {
        return $this->card;
    }

    public function setCard(?card $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }
}
