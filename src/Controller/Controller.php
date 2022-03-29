<?php

namespace App\Controller;
use DateInterval;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use App\Entity\Produit;
use App\Entity\Article;
use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Categorie;
use App\Entity\Ingredient;
use App\Entity\TypeIngredient;
use App\Entity\IngredientsCateg;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;


class Controller extends AbstractController
{
    
    /**
     * @Route("/Facture",name="Facture") 
     */
    public function Facture(Request $request){ // fonction executé lors de la requete du client
        $repositoryFacture = $this->getDoctrine()->getRepository(Facture::class);
        // on récupère le "repository" de la classe facture
        $Facture = $repositoryFacture->findOneBy(['id'=>$_GET['idCommande']]);
        // on effectue une requete a travers le repository pour récupérer l'objet facture
        // corrspondant a la commande demandé 
        return $this->render('Pages/Facture.html.twig',[ // on retourne au client le template twig 
                'Facture'=>$Facture, // parametres a envoyer a la vue
        ]
    );
    }
    
 /**
     * @Route("/ClientSuppr",name="ClientSuppr")
     */
    public function ClientSuppr(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager=$this->getDoctrine()->getManager();
        $idClient=$_GET['idClient'];
        
        $repositoryClient = $this->getDoctrine()->getRepository(Client::class); 
        $Client = $repositoryClient->findOneBy(['id'=>$idClient]);
        foreach($Client->getFactures() as $facture){

            foreach($facture->getArticles() as $f){
                $entityManager->remove($f);
            $entityManager->flush();
            }
            $entityManager->remove($facture);
            $entityManager->flush();
        }
        $entityManager->remove($Client);
        $entityManager->flush();
        return $this->redirectToRoute("Clients");

    }
    /**
     * @Route("/ArticleSuppr",name="articleSuppr")
     */
    public function ArticleSuppr(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager=$this->getDoctrine()->getManager();
        $idArticle=$_GET['idArticle'];
        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class); 
        $Article = $repositoryArticles->findOneBy(['id'=>$idArticle]);

        $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class); 
        $idCommande = $_GET['idCommande'];
        $Commande = $repositoryCommandes->findOneBy(['id'=>$idCommande]);
        
        $entityManager->remove($Article);
        $entityManager->flush();


        return $this->redirectToRoute("Modif",['idCommande'=>$idCommande]);





    }

/**
     * @Route("/PasserCommande",name="PasserCommande")
     */
    public function PasserCommande(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $userObject=$this->getUser();
        $username = $userObject->getUsername();
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
            if(isset($_GET["produits"])){
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
                    $article->setCategorie($Categorie);
                    echo "Calcul : ".$article->getQte()."x".($article->getCategorie()->getPoids() / 1000)."x".$article->getProduit()->getPrixUnitaire();
                    $total = $article->getQte() * ($article->getCategorie()->getPoids() / 1000) * $article->getProduit()->getPrixUnitaire();
                    echo "<br> calculé : ".$total;
                    $article->setTotal($total);
                    
                    
    
    
    
                    $total = 0;
                    foreach($Commande->getArticles()  as $article){
                    $total = $total + $article->getTotal();
                    
                    }
                    
                    $Commande->setTotal($total);
                    $entityManager->persist($Commande);
                    $entityManager->persist($article);
                    
                    $entityManager->flush();
                    
                    }
                    
            }
            foreach($Commande->getArticles() as $article){
                $verif = 0;
                foreach($Commande->getArticles() as $articleVerif){
                    if($article->getProduit() == $articleVerif->getProduit()){
                        

                    }
                }

            }
            
                $entityManager->flush();header('Location:ConsulterCommande?idCommande='.$Commande->getId());die;
        }else{
            $dateSelectionne = new \DateTime();
            $dateSelectionne->add(new DateInterval('P2D'));
            return $this->render('Pages/PasserCommande.html.twig',[
                'Commande'=>$Commande,'Produits'=>$Produits,'Categories'=>$Categories
                ,'Clients'=>$Clients,'dateDuJour'=>$dateSelectionne,
                
        ]
    
    );
        }
        
        

       }
    /**
     * @Route("/Modif",name="Modif")
     */
    public function Modif(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        
    //ON RECUPERE LE MANAGER
        $entityManager=$this->getDoctrine()->getManager();
    //ON RECUPERE L'OBJET COMMANDE
        $repositoryCommandes = $this->getDoctrine()->getRepository(Facture::class); 
        $idCommande = $_GET['idCommande'];
        $Commande = $repositoryCommandes->findOneBy(['id'=>$idCommande]);

    //ON PURGE LA COMMANDE DE CES ARTICLES
        foreach($Commande->getArticles() as $article){
            $entityManager->remove($article);
            $entityManager->flush();
        }
    //ON RECUPERE LES DONNEES DU FORM
        
        $idClient = $_GET['idClient'];
        $date = $_GET['date'];
        $listeIdProduits = [];
        if(isset($_GET['produits'])){
        foreach($_GET['produits'] as $idProduits){
            $listeIdProduits[] = $idProduits;
        }}
        $listeQuantites = [];
        if(isset($_GET['qte'])){
        foreach($_GET['qte'] as $qte){
            $listeQuantites[] = $qte;
        }}
        $listePoids = [];
        if(isset($_GET['Poids'])){
        foreach($_GET['Poids'] as $poids){
            $listePoids[] = $poids;
        }}

    //ON MET A JOUR LES ARTICLES A PARTIR DU FORM
    $nombreArticles = 0;
        foreach($listeIdProduits as $idProduit){
            $nombreArticles = $nombreArticles +1;
        }$totalCommande = 0;
        for($nbrArt = 0; $nbrArt!=$nombreArticles;$nbrArt++){
            $article = new Article();

            $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
            $Produit = $repositoryProduits->findOneBy(['id'=>$listeIdProduits[$nbrArt]]);
            $article->setProduit($Produit);
            $article->setQte($listeQuantites[$nbrArt]);
            $repositoryCategorie = $this->getDoctrine()->getRepository(Categorie::class);
            $Categorie = $repositoryCategorie->findOneBy(['id'=>$listePoids[$nbrArt]]);  
            $article->setCategorie($Categorie);
            $total = $article->getQte() * ($article->getCategorie()->getPoids() / 1000);$total = $total * $article->getProduit()->getPrixUnitaire();
            $article->setTotal($total);
            $Commande->addArticle($article);
            $entityManager->persist($article);
            $entityManager->flush();
            $totalCommande = $totalCommande + $total;
            $Commande->setTotal($totalCommande);
            $entityManager->persist($Commande);
            $entityManager->flush();

        }
        
        return $this->redirectToRoute("ConsulterCommande",['idCommande'=>$idCommande]);
    
    }
    /**
     * @Route("/Clients",name="Clients")
     */
    public function Clients(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        $userObject=$this->getUser();
        $username = $userObject->getUsername();
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
 return $this->render('Pages/Clients.html.twig',[
                'Factures'=>$Factures,
                'Clients'=>$Clients,
                'Articles'=>$Articles,
                'Produits'=>$Produits,
                'Categories'=>$Categories,
                'nomUtilisateur'=>$username,
                'selected'=>4,
                
 ]);

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


        $userObject=$this->getUser();
        $username = $userObject->getUsername();
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
                'nomUtilisateur'=>$username,
                'selected'=>1,
                
        ]

    );


    }
    /**
     * @Route("/ProduitSuppr",name="ProduitSuppr")
     */
    public function ProduitSuppr(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
     * @Route("/ClientEdit",name="ClientEdit")
     */
    public function ClientEdit(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        if(isset($_GET['nomClient']) ){
             
            $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
            $numTel = $_GET['numTel'];
            $Email = $_GET['Email'];
            $Addresse = $_GET['Adresse'];
            $repositoryClient= $this->getDoctrine()->getRepository(Client::class);
            $clientId = $_GET['prodId'];
            $Client = $repositoryClient->findOneById($clientId);
            $manager = $this->getDoctrine()->getManager();
                $Client->setNomClient($_GET['nomClient'])->setNumTel($numTel)->setEmail($Email)->setAdresse($Addresse);
                
    
                $manager->persist($Client);
                $manager->flush();header('Location:Clients');die;
    
            }

        $repositoryClient= $this->getDoctrine()->getRepository(Client::class);
        $clientId = $_GET['idClient'];
        $Client = $repositoryClient->findOneById($clientId);
        return $this->render('Pages/EditClient.html.twig',[
            'Client'=>$Client,
        ]);

    }

    /**
     * @Route("/ProduitEdit",name="ProduitEdit")
     */
    public function ProduitEdit(Request $request){
        $idProduit = $request->query->get('idProduitsEdit');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
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
            $Produit[0]->setNomProduit($_GET['nom'])->setKgPateParKg($_GET['kgpate'])->setPrixUnitaire($_GET['prixKg']);
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
     * @Route("/NouveauClient",name="NouveauClient")
    */
public function NouveauClient(Request $request){
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    if(isset($_GET['nomClient']) ){
             
        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $numTel = $_GET['numTel'];
        $Email = $_GET['Email'];
        $Addresse = $_GET['Adresse'];

        $Client = new Client();
        $manager = $this->getDoctrine()->getManager();
            $Client->setNomClient($_GET['nomClient'])->setNumTel($numTel)->setEmail($Email)->setAdresse($Addresse);
            

            $manager->persist($Client);
            $manager->flush();header('Location:Clients');die;

        }



    return $this->render('Pages/NouveauClient.html.twig',[]

);


}
    /**
     * @Route("/NouveauProduit",name="NouveauProduit")
*/
    public function NouveauProduit(Request $request){
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

        if(isset($_GET['nom']) && isset($_GET['kgpate'])){
             
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        
        $Produit = new Produit();
        $manager = $this->getDoctrine()->getManager();
            $Produit->setNomProduit($_GET['nom'])->setKgPateParKg($_GET['kgpate'])->setPrixUnitaire($_GET['prixKg']);
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userObject=$this->getUser();
        $username = $userObject->getUsername();
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
                'nomUtilisateur'=>$username,
                'selected'=>3,
                
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userObject=$this->getUser();
        $username = $userObject->getUsername();
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        $Produits = $repositoryProduits->findAll();

        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class);
        $Articles = $repositoryArticles->findAll();       

        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Clients = $repositoryClients->findAll();   

        $repositoryFactures = $this->getDoctrine()->getRepository(Facture::class);
        $Factures = $repositoryFactures->findAll();
        $entityManager=$this->getDoctrine()->getManager();
        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();
        
        $dateSelectionne = new \DateTime('NOW');
        if(isset($_GET['Date'])){
            
            $dateSelectionne = new \DateTime($_GET['Date']);
            $i = 0;
            

            while ($i < count($Factures))
            {
                if($Factures[$i]->getDate()->format('Y-m-d') != $_GET['Date']){
                    
                    $Factures[$i] = NULL;
                }
                $i++;
            }



        }else{
            
            $j = 0;
            while ($j < count($Factures))
            {   
                if($Factures[$j]->getDate()->format('Y-m-d') != $dateSelectionne->format('Y-m-d')){
                    
                    
                    $Factures[$j] = NULL;
                }
                $j++;
            }
        }
        $newProduits = [];
        foreach($Produits as $produit){
            
            $metadata = $entityManager->getClassMetadata(Produit::class);
           
            if($produit->getArticles()->isEmpty()){
               
            }else{
                $article = $produit->getArticles();
                $commande = $article[0]->getFacture();
                if($commande->getDate() == $dateSelectionne){
                    $newProduits[] = $produit;
                }
                
            }
            
            
            
            
            
        }
        $Factures = array_filter($Factures); 
        return $this->render('Pages/CommandesAgregation.html.twig',[
                'Factures'=>$Factures,
                'Clients'=>$Clients,
                'Articles'=>$Articles,
                'Produits'=>$newProduits,
                'Categories'=>$Categories,
                'nomUtilisateur'=>$username,
                'dateSelection'=>$dateSelectionne,
                'selected'=>2,
                
        ]

    );
    }


    /**
     * @Route("/AgregationIngredients",name="AgregationIngredients")
     */
    public function AgregationIngredients(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userObject=$this->getUser();
        $username = $userObject->getUsername();
        $repositoryProduits = $this->getDoctrine()->getRepository(Produit::class);
        $Produits = $repositoryProduits->findAll();

        $repositoryArticles = $this->getDoctrine()->getRepository(Article::class);
        $Articles = $repositoryArticles->findAll();       

        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);
        $Clients = $repositoryClients->findAll();   

        $repositoryFactures = $this->getDoctrine()->getRepository(Facture::class);
        $Factures = $repositoryFactures->findAll();

        $repositoryTypeIngredients = $this->getDoctrine()->getRepository(TypeIngredient::class);
        $TypeIngredients = $repositoryTypeIngredients->findAll();

        $repositoryIngredients = $this->getDoctrine()->getRepository(Ingredient::class);
        $Ingredients = $repositoryIngredients->findAll();


        $repositoryIngredientsCateg = $this->getDoctrine()->getRepository(IngredientsCateg::class);
        $IngredientsCateg = $repositoryIngredientsCateg->findAll();

        $entityManager=$this->getDoctrine()->getManager();
        $repositoryCategories = $this->getDoctrine()->getRepository(Categorie::class);
        $Categories = $repositoryCategories->findAll();
        $newProduits = [];
        foreach($Produits as $produit){
            
            $metadata = $entityManager->getClassMetadata(Produit::class);
            $collection = new ArrayCollection([1, 2, 3]);
            if($produit->getArticles()->isEmpty()){
               
            }else{
                
                $newProduits[] = $produit;
            }
            
            
            
            
            
        }
        $dateSelectionne = new \DateTime('NOW');
        if(isset($_GET['Date'])){
            
            $dateSelectionne = new \DateTime($_GET['Date']);
            $i = 0;
            

            while ($i < count($Factures))
            {
                if($Factures[$i]->getDate()->format('Y-m-d') != $_GET['Date']){
                    
                    $Factures[$i] = NULL;
                }
                $i++;
            }



        }else{
            
            $j = 0;
            while ($j < count($Factures))
            {   
                if($Factures[$j]->getDate()->format('Y-m-d') != $dateSelectionne->format('Y-m-d')){
                    
                    
                    $Factures[$j] = NULL;
                }
                $j++;
            }
        }
        $Factures = array_filter($Factures); 
        return $this->render('Pages/CommandesAgregationIngredients.html.twig',[
                'Factures'=>$Factures,
                'Clients'=>$Clients,
                'Articles'=>$Articles,
                'Produits'=>$newProduits,
                'Categories'=>$Categories,
                'Ingredients'=>$Ingredients,
                'TypeIngredients'=>$TypeIngredients,
                'nomUtilisateur'=>$username,
                'dateSelection'=>$dateSelectionne,
                'IngredientsCateg'=>$IngredientsCateg,
                'selected'=>2,
                
        ]

    );
    }


    
    

 

}
