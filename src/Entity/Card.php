<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type_card;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $object;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_publication;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $type_f;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $statu_f;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTypeCard(): ?string
    {
        return $this->type_card;
    }

    public function setTypeCard(string $type_card): self
    {
        $this->type_card = $type_card;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTypeF(): ?string
    {
        return $this->type_f;
    }

    public function setTypeF(?string $type_f): self
    {
        $this->type_f = $type_f;

        return $this;
    }

    public function getStatuF(): ?string
    {
        return $this->statu_f;
    }

    public function setStatuF(?string $statu_f): self
    {
        $this->statu_f = $statu_f;

        return $this;
    }
}
