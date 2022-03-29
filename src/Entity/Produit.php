<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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
    private $nomProduit;

    /**
     * @ORM\Column(type="float")
     */
    private $prixUnitaire;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="produit", orphanRemoval=true)
     */
    private $Articles;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categorie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $QteEnStock;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $kgPateParKg;







    /**
     * @ORM\Column(type="integer", nullable=true)
     */


    public function __construct()
    {
        $this->Articles = new ArrayCollection();
        
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->Articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->Articles->contains($article)) {
            $this->Articles[] = $article;
            $article->setProduit($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->Articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getProduit() === $this) {
                $article->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getQteEnStock(): ?int
    {
        return $this->QteEnStock;
    }

    public function setQteEnStock(?int $QteEnStock): self
    {
        $this->QteEnStock = $QteEnStock;

        return $this;
    }

    public function getKgPateParKg(): ?float
    {
        return $this->kgPateParKg;
    }

    public function setKgPateParKg(?float $kgPateParKg): self
    {
        $this->kgPateParKg = $kgPateParKg;

        return $this;
    }













}
