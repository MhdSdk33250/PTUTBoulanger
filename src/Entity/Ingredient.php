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
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="ingredients")
     */
    private $produit;

    /**
     * @ORM\Column(type="float")
     */
    private $qte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeQte;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQte(): ?float
    {
        return $this->qte;
    }

    public function setQte(float $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getTypeQte(): ?string
    {
        return $this->typeQte;
    }

    public function setTypeQte(string $typeQte): self
    {
        $this->typeQte = $typeQte;

        return $this;
    }
}
