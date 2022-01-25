<?php

namespace App\DataFixtures;
use App\Entity\Produit;
use App\Entity\Article;
use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // on cree les catégories 

        $categorie250 = new categorie();
        $categorie250->setPoids(500);
        $manager->persist($categorie250);

        $categorie500 = new categorie();
        $categorie500->setPoids(1000);
        $manager->persist($categorie500);

        $categorie1000 = new categorie();
        $categorie1000->setPoids(2000);
        $manager->persist($categorie1000);
        //creation de 3 produit fictif
        $produit1 = new Produit();
        $produit1 -> setNomProduit("pain au noix")->setKgPateParKg(1)
        ->setPrixUnitaire(5.2);
        $manager->persist($produit1);

        $produit2 = new Produit();
        $produit2 -> setNomProduit("pain d'épice")->setKgPateParKg(0.8)
        ->setPrixUnitaire(5.2);
        $manager->persist($produit2);

        $produit3 = new Produit();
        $produit3 -> setNomProduit("croissants")->setKgPateParKg(1.3)
        ->setPrixUnitaire(5.2);
        $manager->persist($produit3);

        //creation de 2 client fictif

        $client1 = new Client();
        $client1 -> setNomClient("sdk");
        $manager->persist($client1);

        $client2 = new Client();
        $client2 -> setNomClient("Thomassin");
        $manager->persist($client2);

       




        //Creation d'une commande de 2 articles produit1 250 et de 1 article produit en 500g pour le client 1 (seddik)

        //on crée la commande (commande = facture)
        $facture1 = new Facture();
        $facture1->setDate(new \DateTime())
            ->setClient($client1);
        $manager->persist($facture1);
        
        //on prend 2 articles produit1 en 500g
        $article1 = new Article();
        $article1->setQte(2)
        ->setTotal(0)
        ->setCategorie($categorie250)
        ->setProduit($produit1);
        $manager->persist($article1);
        //on prend 1 articles produit2
        $article2 = new Article();
        $article2->setQte(1)
        ->setTotal(0)
        ->setCategorie($categorie500)
        ->setProduit($produit1);
        $manager->persist($article2);

        $article3 = new Article();
        $article3->setQte(1)
        ->setTotal(0)
        ->setCategorie($categorie1000)
        ->setProduit($produit3);
        $manager->persist($article3);

        //on ajoute les articles dans la facture/commande

        $facture1->addArticle($article1);
        $facture1->addArticle($article2);
        $facture1->addArticle($article3);
        //------------------------------------------





        $facture2 = new Facture();
        $facture2->setDate(new \DateTime())
            ->setClient($client2);
        $manager->persist($facture2);
        
        //on prend 2 articles produit1 en 500g
        $article10 = new Article();
        $article10->setQte(2)
        ->setTotal(0)
        ->setCategorie($categorie250)
        ->setProduit($produit1);
        $manager->persist($article10);
        //on prend 1 articles produit2
        $article20 = new Article();
        $article20->setQte(3)
        ->setTotal(0)
        ->setCategorie($categorie500)
        ->setProduit($produit3);
        $manager->persist($article20);

        $article30 = new Article();
        $article30->setQte(5)
        ->setTotal(0)
        ->setCategorie($categorie1000)
        ->setProduit($produit3);
        $manager->persist($article30);

        //on ajoute les articles dans la facture/commande

        $facture2->addArticle($article10);
        $facture2->addArticle($article20);
        $facture2->addArticle($article30);





        $manager->flush();
    }
}
