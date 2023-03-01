<?php

namespace App\Entity;

use Assert\NotBlank;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecetteRepository;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[UniqueEntity('Name')]
#[ORM\HasLifecycleCallbacks]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:2, max:50)]
    private ?string $Name = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThan(1440)]
    private ?int $executionTime = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(50)]
    private ?int $nbPersonnes = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(5)]
    private ?int $Difficulty = null;

    
    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero()]
    #[Assert\LessThan(1000)]
    private ?float $Price = null;

    #[ORM\Column]
    private ?bool $Favorite = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $ModifiedAt = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $Description = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class)]
    private Collection $IngredientsIn;

   

    /**
     * Constructeur permettant de définir la date de création de l'objet
     * et crée une liste à partir des ingrédients de la classe Ingredient
     */
    public function __construct()
    {
        $this->CreatedAt = new \DateTimeImmutable();
        $this->ModifiedAt = new \DateTimeImmutable();
        $this->IngredientsIn = new ArrayCollection();
        
    }
    /**
     * Fonction permettant de mettre à jour la date de modification de l'objet
     * grâce à prepersist
     */
    #[ORM\PrePersist()]
    public function setModifiedAtValue()
    {
        $this->ModifiedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getExecutionTime(): ?int
    {
        return $this->executionTime;
    }

    public function setExecutionTime(?int $executionTime): self
    {
        $this->executionTime = $executionTime;

        return $this;
    }

    public function getNbPersonnes(): ?int
    {
        return $this->nbPersonnes;
    }

    public function setNbPersonnes(?int $nbPersonnes): self
    {
        $this->nbPersonnes = $nbPersonnes;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->Difficulty;
    }

    public function setDifficulty(?int $Difficulty): self
    {
        $this->Difficulty = $Difficulty;

        return $this;
    }

   

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(?float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function isFavorite(): ?bool
    {
        return $this->Favorite;
    }

    public function setFavorite(?bool $Favorite): self
    {
        $this->Favorite = $Favorite;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->ModifiedAt;
    }

    public function setModifiedAt(\DateTimeImmutable $ModifiedAt): self
    {
        $this->ModifiedAt = $ModifiedAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredientsIn(): Collection
    {
        return $this->IngredientsIn;
    }

    public function addIngredientsIn(Ingredient $ingredientsIn): self
    {
        if (!$this->IngredientsIn->contains($ingredientsIn)) {
            $this->IngredientsIn->add($ingredientsIn);
        }

        return $this;
    }

    public function removeIngredientsIn(Ingredient $ingredientsIn): self
    {
        $this->IngredientsIn->removeElement($ingredientsIn);

        return $this;
    }

   
}
