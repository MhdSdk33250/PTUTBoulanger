<?php

namespace App\Controller;
use App\Entity\User;
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
     * @Route("/ArticleSuppr",name="articleSuppr")
     */
    public function ArticleSuppr(Request $request){
        $entityManager=$this->getDoctrine()->getManager();
        $idArticle=$_GET['idArticle'];
        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class); 
        $Article = $repositoryArticles->findOneBy(['id'=>$idArticle]);

        $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class); 
        $idCommande = $_GET['idCommande'];
        $Commande = $repositoryCommandes->findOneBy(['id'=>$idCommande]);

        $entityManager->remove($Article);
        $entityManager->flush();


        return $this->redirectToRoute("ConsulterCommande",['idCommande'=>$idCommande]);





    }

/**
     * @Route("/PasserCommande",name="PasserCommande")
     */
    public function PasserCommande(Request $request){

        //
        $entityManager=$this->getDoctrine()->getManager();
        //$idCommande = $request->query->get('idCommande');
        $Commande = new Facture();
        
        $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class);
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        $Produits = $repositoryProduits->findAll();
        
        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Clients = $repositoryClients->findAll(); 
        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();
        if(isset($_GET['idClient'])){
           

            $idClient = $_GET['idClient'];
            $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
            $Client = $repositoryClients->findOneBy(['id'=>$idClient]);



            $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class); 
            $Commande = new Facture();
            $Commande->setClient($Client);
            $Commande->setDate(new \DateTime($_GET['date']));

            $nbrArticles=sizeof($_GET["produits"]);
            for($i = 0;$i != $nbrArticles;$i++){
                $article = new Article();
                
                
                $idProduit = $_GET["produits"][$i];
                $qteArticle = $_GET["qte"][$i];
                $idCategorieArticle = $_GET["Poids"][$i];

                $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
                $Produit = $repositoryProduits->findOneBy(['id'=>$idProduit]);  

                $repositoryCategorie = $this->getDoctrine()->getRepository(Categorie::class);
                $Categorie = $repositoryCategorie->findOneBy(['id'=>$idCategorieArticle]);  
                $Commande->addArticle($article);
                $article->setProduit($Produit);
                $article->setQte($qteArticle);
                $article->setTotal(0);
                $article->setCategorie($Categorie);
                $entityManager->persist($article);
                $entityManager->persist($Commande);
                $entityManager->flush();
                
                }header('Location:Accueil');die;
        }else{
            return $this->render('Pages/PasserCommande.html.twig',[
                'Commande'=>$Commande,'Produits'=>$Produits,'Categories'=>$Categories
                ,'Clients'=>$Clients
                
        ]
    
    );
        }
        
        

       }
    /**
     * @Route("/Modif",name="Modif")
     */
    public function Modif(Request $request){
        
        $nbrArticlesApresModif=sizeof($_GET["produits"]);
        
        $idClient = $_GET['idClient'];
         
        

        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Client = $repositoryClients->findOneBy(['id'=>$idClient]);

        $manager = $this->getDoctrine()->getManager();
        
        //nouveau client
        $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class); 
        $idCommande = $_GET['idCommande'];
        $Commande = $repositoryCommandes->findOneBy(['id'=>$idCommande]);
        
        
        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class); 
        $articles = $Commande->getArticles();;
        $articleVerifRedondance = [];
        


        for($i = 0;$i != $nbrArticlesApresModif;$i++){
            $idProduit = $_GET["produits"][$i];
            $qteArticle = $_GET["qte"][$i];
            $idCategorieArticle = $_GET["Poids"][$i];

            $articleVerifRedondance[$i][] = $idProduit;
            
            $articleVerifRedondance[$i][] = $idCategorieArticle;

        }
        
        
        
        
        
        
        
        
        
        

        

        
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
            
            
            $articleVerifRedondance[] = $articles[$i];
            
            
        }
        

        for($j = $nbrArticlesAvantModif;$j != $nbrArticlesApresModif;$j++){
            
            $articles[$j] = new Article();
            
            
            $idProduit = $_GET["produits"][$j];
            $qteArticle = $_GET["qte"][$j];
            $idCategorieArticle = $_GET["Poids"][$j];

            $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
            $Produit = $repositoryProduits->findOneBy(['id'=>$idProduit]);  

            $repositoryCategorie = $this->getDoctrine()->getRepository(Categorie::class);
            $Categorie = $repositoryCategorie->findOneBy(['id'=>$idCategorieArticle]);  
            
            $articles[$j]->setProduit($Produit);
            $articles[$j]->setQte($qteArticle);
            $articles[$j]->setCategorie($Categorie);
            $articles[$j]->setTotal("0")
            ->setFacture($Commande);
            
            
            $manager->persist($articles[$j]);
            $Commande->addArticle($articles[$j]);
            

            
            $articleVerifRedondance[] = $articles[$j];
            
            //LIER LOBJET A LA BD
            //PERSIST ET FLUSH LOBJET!!!
            
        }
        foreach($Commande->getArticles()  as $article){
            echo $article->getQte();
        }
        
        
        

        

        
        $manager->persist($Client);
        
        //on crée la commande (commande = facture)
        
        $Commande->setDate(new \DateTime($_GET['date']))
            ->setClient($Client);
        $manager->persist($Commande);
        

        $taille = sizeof($articleVerifRedondance);
        $articleVerifRedondance = array_intersect_key($articleVerifRedondance, array_unique(array_map('serialize', $articleVerifRedondance)));
        
        
        if(sizeof($articleVerifRedondance) != $taille ){
            echo "deux articles sont identiques, veuillez saisir un article different par ligne";die;
            
        }else{
            echo "ok";$manager->flush();
        }
        
        return $this->redirectToRoute("ConsulterCommande",['idCommande'=>$idCommande]);
    
    }

    /**
     * @Route("/Accueil",name="Accueil")
     */
    public function Accueil(){


        //$userObject=$this->getUser();
        //$username = $userObject->getUsername();
        //if($username == 'mhdi.seddik@gmail.com')
        //{
          //  $repositoryUsers = $this->getDoctrine()->getRepository(User::class);
            
          // $user = $repositoryUsers->findOneBy(['email'=>$username]);
           //$user->setRoles( array('ROLE_ADMIN') );
           //$entityManager=$this->getDoctrine()->getManager();
           //$entityManager->flush();
           
      // }

        $this->denyAccessUnlessGranted('ROLE_ADMIN');



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
        foreach($Commande->getArticles() as $article){
            $entityManager->remove($article);
        }
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
     * @Route("/ProduitEdit",name="ProduitEdit")
     */
    public function ProduitEdit(Request $request){
        $idProduit = $request->query->get('idProduitsEdit');

        
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        $Produits = $repositoryProduits->findAll();
        $Produit = $repositoryProduits->findBy(['id'=>$idProduit]);

        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class);
        $Articles = $repositoryArticles->findAll();       

        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Clients = $repositoryClients->findAll();   

        $repositoryFactures = $this->getDoctrine()->getRepository(Facture::class);
        $Factures = $repositoryFactures->findAll();

        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();

        if(isset($_GET['nom']) && isset($_GET['kgpate'])){
        $idProduit = $_GET['idProduit'];       
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        
        $Produit = $repositoryProduits->findBy(['id'=>$idProduit]);
        $manager = $this->getDoctrine()->getManager();
            $Produit[0]->setNomProduit($_GET['nom'])->setKgPateParKg($_GET['kgpate']);
            $manager->persist($Produit[0]);
            $manager->flush();header('Location:Produits');die;

        }
        return $this->render('Pages/ProduitEdit.html.twig',[
                'Factures'=>$Factures,
                'Clients'=>$Clients,
                'Articles'=>$Articles,
                'Produits'=>$Produits,
                'Categories'=>$Categories,
                'Produit'=>$Produit,
                
        ]

    );}
    /**
     * @Route("/NouveauProduit",name="NouveauProduit")
*/
    public function NouveauProduit(Request $request){
        

        
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

        if(isset($_GET['nom']) && isset($_GET['kgpate'])){
             
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        
        $Produit = new Produit();
        $manager = $this->getDoctrine()->getManager();
            $Produit->setNomProduit($_GET['nom'])->setKgPateParKg($_GET['kgpate'])->setPrixUnitaire(0);
            $manager->persist($Produit);
            $manager->flush();header('Location:Produits');die;

        }
        return $this->render('Pages/ProduitAjout.html.twig',[
                'Factures'=>$Factures,
                'Clients'=>$Clients,
                'Articles'=>$Articles,
                'Produits'=>$Produits,
                'Categories'=>$Categories,
                       
        ]

    );}
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
