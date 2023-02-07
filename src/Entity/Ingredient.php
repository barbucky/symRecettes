<?php

namespace App\Entity;

use App\Repository\IngredientRepository;

use Doctrine\ORM\Mapping as ORM;


 /**chemin composant symfony que l'on définir comme Assert */
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    /**On ne veut pas que la donnée soit nulle */
    /**On veut que le nom soit composé de 2 lettres minimums et 50 au max */
    #[Assert\NotBlank()]
    #[Assert\Length(min:2, max:50)]
    private ?string $name = null;

    #[ORM\Column]
    /**On ne veut pas que la donnée soit nulle */
    /**On veut que le prix soit strictement positif ET inférieur à 200 */
    #[Assert\NotNull()]
    #[Assert\Positive()]
    #[Assert\GreaterThan(0)]
    #[Assert\LessThan(200)]
    private ?float $price = null;

    #[ORM\Column]
    /**On ne veut pas que la donnée soit nulle */
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt = null;

    /**Par convenance, on souhaite que la date soit entrée automatiquement
     * à la création de l'ingrédient.
     * On met en place un constructeur
     */
    public function __construct()
    {
        // le '\' permet de ne pas avoir à importer l'objet DateTimeImmutable
        $this->createdAt = new \DateTimeImmutable();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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
}
