<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $qte;

    /**
     * @ORM\ManyToOne(targetEntity=TypeIngredient::class, inversedBy="Ingredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeIngredient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=IngredientsCateg::class, inversedBy="Ingredients")
     */
    private $ingredientsCateg;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getTypeIngredient(): ?TypeIngredient
    {
        return $this->typeIngredient;
    }

    public function setTypeIngredient(?TypeIngredient $typeIngredient): self
    {
        $this->typeIngredient = $typeIngredient;

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

    public function getIngredientsCateg(): ?IngredientsCateg
    {
        return $this->ingredientsCateg;
    }

    public function setIngredientsCateg(?IngredientsCateg $ingredientsCateg): self
    {
        $this->ingredientsCateg = $ingredientsCateg;

        return $this;
    }
}
