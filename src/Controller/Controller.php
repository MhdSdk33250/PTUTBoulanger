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
    public function Modif(Request $request){
        $manager = $this->getDoctrine()->getManager();
        
        //nouveau client
        $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class); 
        $idCommande = $_GET['idCommande'];
        $Commande = $repositoryCommandes->findOneBy(['id'=>$idCommande]);
        
        
        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class); 
        $articles = $Commande->getArticles();
        
        
        
        

        

        
        $nbrArticlesAvantModif = 0;
        foreach($articles as $article){
            $nbrArticlesAvantModif = $nbrArticlesAvantModif +1;
        }
        
        

        for($i = 0;$i != $nbrArticlesAvantModif;$i++){
            $idProduit = $_GET["produits"][$i];
            $qteArticle = $_GET["qte"][$i];
            $idCategorieArticle = $_GET["Poids"][$i];

            $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
            $Produit = $repositoryProduits->findOneBy(['id'=>$idProduit]);  

            $repositoryCategorie = $this->getDoctrine()->getRepository(Categorie::class);
            $Categorie = $repositoryCategorie->findOneBy(['id'=>$idCategorieArticle]);  
            
            $articles[$i]->setProduit($Produit);
            $articles[$i]->setQte($qteArticle);
            $articles[$i]->setCategorie($Categorie);
            $manager->flush();
            
        }
        
        

        $idClient = $_GET['idClient'];
         
        

        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Client = $repositoryClients->findOneBy(['id'=>$idClient]);  

        
        $manager->persist($Client);
        $manager->flush();
        //on crée la commande (commande = facture)
        
        $Commande->setDate(new \DateTime($_GET['date']))
            ->setClient($Client);
        $manager->persist($Commande);
        $manager->flush();

        
        
        return $this->redirectToRoute("ConsulterCommande",['idCommande'=>$idCommande]);
    
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
        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Clients = $repositoryClients->findAll(); 
        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();
        
        

        return $this->render('Pages/ConsulterCommande.html.twig',[
            'Commande'=>$Commande,'Produits'=>$Produits,'Categories'=>$Categories
            ,'Clients'=>$Clients
            
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
