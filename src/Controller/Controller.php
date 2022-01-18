<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Article;
use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class Controller extends AbstractController
{

    /**
     * @Route("/Modif",name="Modif")
     */
    public function Modif(){
    
        var_dump($_GET);
        die;
    
    }

    /**
     * @Route("/Accueil",name="Accueil")
     */
    public function Accueil(){



        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        $Produits = $repositoryProduits->findAll();

        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class);
        $Articles = $repositoryArticles->findAll();       

        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Clients = $repositoryClients->findAll();   

        $repositoryFactures = $this->getDoctrine()->getRepository(Facture::class);
        $Factures = $repositoryFactures->findAll();

        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();


        return $this->render('Pages/AccueilAdmin.html.twig',[
                'Factures'=>$Factures,
                'Clients'=>$Clients,
                'Articles'=>$Articles,
                'Produits'=>$Produits,
                'Categories'=>$Categories,
                
        ]

    );


    }
    /**
     * @Route("/ProduitSuppr",name="ProduitSuppr")
     */
    public function ProduitSuppr(Request $request){

        //
        $entityManager=$this->getDoctrine()->getManager();
        $idProduit = $request->query->get('idProduitsuppr');
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        
        $produit = $repositoryProduits->findOneBy(['id'=>$idProduit]);
        
        $entityManager->remove($produit);
        $entityManager->flush();


        return $this->redirectToRoute('Produits');
        
    }

    /**
     * @Route("/CommandeSuppr",name="CommandeSuppr")
     */
    public function CommandeSuppr(Request $request){

        //
        $entityManager=$this->getDoctrine()->getManager();
        $idCommande = $request->query->get('CommandeSuppr');
        $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class);

        
        
        $Commande = $repositoryCommandes->findOneBy(['id'=>$idCommande]);
        
        $entityManager->remove($Commande);
        $entityManager->flush();


        return $this->redirectToRoute('Accueil');
        
    }
    /**
     * @Route("/ConsulterCommande",name="ConsulterCommande")
     */
    public function ConsulterCommande(Request $request){

        //
        $entityManager=$this->getDoctrine()->getManager();
        $idCommande = $request->query->get('idCommande');
        $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class);
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        $Produits = $repositoryProduits->findAll();
        $Commande = $repositoryCommandes->findOneBy(['id'=>$idCommande]);
        
        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();
        
        

        return $this->render('Pages/ConsulterCommande.html.twig',[
            'Commande'=>$Commande,'Produits'=>$Produits,'Categories'=>$Categories
            
    ]

);
        
    }

    /**
     * @Route("/Produits",name="Produits")
     */
    public function Produits(){



        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        $Produits = $repositoryProduits->findAll();

        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class);
        $Articles = $repositoryArticles->findAll();       

        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Clients = $repositoryClients->findAll();   

        $repositoryFactures = $this->getDoctrine()->getRepository(Facture::class);
        $Factures = $repositoryFactures->findAll();

        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();


        return $this->render('Pages/Produits.html.twig',[
                'Factures'=>$Factures,
                'Clients'=>$Clients,
                'Articles'=>$Articles,
                'Produits'=>$Produits,
                'Categories'=>$Categories,
                
        ]

    );


    }

    /**
     * @Route("/",name="redirectRoot")
     */
    public function RedirectRoot(){

        return $this->redirectToRoute('Accueil');
    }
    
    

    /**
     * @Route("/Commandes",name="Agregations des commandes")
     */
    public function CommandesAgregation(){



        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        $Produits = $repositoryProduits->findAll();

        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class);
        $Articles = $repositoryArticles->findAll();       

        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Clients = $repositoryClients->findAll();   

        $repositoryFactures = $this->getDoctrine()->getRepository(Facture::class);
        $Factures = $repositoryFactures->findAll();

        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();


        return $this->render('Pages/CommandesAgregation.html.twig',[
                'Factures'=>$Factures,
                'Clients'=>$Clients,
                'Articles'=>$Articles,
                'Produits'=>$Produits,
                'Categories'=>$Categories,
                
        ]

    );
    }


    

 

}
