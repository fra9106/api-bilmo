<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhoneRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"phone:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"phone:read"})
     * @Assert\NotBlank(message="Le modèle du produit est obligatoire !")
     * @Assert\Length(min=4, max=255, minMessage="Le modèle du produit doit avoir plus de 4 caractères !")
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"phone:read"})
     * @Assert\NotBlank(message="La couleur du produit est obligatoire !")
     * @Assert\Length(min=4, max=255, minMessage="La couleur du produit doit avoir plus de 2 caractères !")
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     * @Groups({"phone:read"})
     * @Assert\NotBlank(message="La description du produit est obligatoire !")
     * @Assert\Length(min=4, max=255, minMessage="La description du produit doit avoir plus de 10 caractères !")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"phone:read"})
     * @Assert\NotBlank(message="Le prix du produit est obligatoire !")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
