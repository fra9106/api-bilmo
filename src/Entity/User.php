<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email", message="email déjà utilisé !")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users-list:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"users-list:read", "user:read", "shop:read"})
     * @Assert\NotBlank(message=" Merci d'entrer votre email !")
     * @Assert\Email(message="email non valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users-list:read", "user:read", "shop:read"})
     * @Assert\NotBlank(message=" Merci d'entrer votre prénom !")
     * @Assert\Length(min=4, max=255, minMessage="Votre prénom doit comporter plus de 4 caractères !")
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users-list:read", "user:read", "shop:read"})
     * @Assert\NotBlank(message=" Merci d'entrer votre nom !")
     * @Assert\Length(min=4, max=255, minMessage="Votre nom doit comporter plus de 4 caractères !")
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read"})
     * @Assert\NotBlank(message=" Merci d'entrer votre adresse !")
     * @Assert\Length(min=4, max=255, minMessage="Votre adresse doit comporter plus de 10 caractères !")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"user:read"})
     * @Assert\NotBlank(message=" Merci d'entrer votre code postal !")
     * @Assert\Length(min=4, max=255, minMessage="Votre code postal doit comporter plus de 4 caractères !")
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"user:read"})
     * @Assert\NotBlank(message=" Merci d'entrer le nom de votre ville !")
     * @Assert\Length(min=4, max=255, minMessage="Le nom de votre ville doit comporter plus de 3 caractères !")
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user:read"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }
}
