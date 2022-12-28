<?php
///////////////////////////////////////////////////////////Block 1
 session_start(); 
 // Redirection si pas connecté

 require_once 'config.php';
   //echo $_SESSION['pseudo'];
  //print_r($_SESSION['user']);
  if(!isset($_SESSION['user'])){
      header('Location: login.php');
      //echo $_SESSION['user'];
    }

/////////////////////////////////////////////////////////// FIN Block 1
           
///////////////////////////////////////////////////////////Block 2    

//fonction qui affiche apres avoir ajouter des nouveaux utilisateurs ou de nouveaux game type ou min age 
function afficher()
{
	if(require("connexionn.php")) //s il arrive a se connecter
	{
		$req=$access->prepare("SELECT * FROM produit ORDER BY id DESC");

        $req->execute();
        //recuperer les donnes sous forme des objets
        $data = $req->fetchAll(PDO::FETCH_OBJ);

        return $data;

        $req->closeCursor();
	}
}
$Produits=afficher();

// Get the amount of items in the shopping cart.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

/////////////////////////////////////////////////////////// FIN Block 2

///////////////////////////////////////////////////////////block 3
//Récupération de type de jeux depuis la table "gametype"

   $reqGameType = $bdd->prepare('SELECT * FROM gametype ORDER BY id DESC');
   $reqGameType->execute();
  
   $GameTypes= $reqGameType->fetchAll(PDO::FETCH_OBJ);
   foreach($GameTypes as $gameType){
      
  //   //Pour chaque type, on récupére les jeux depuis la table "produit" du même type
        
        //Filtrage des jeux en fcontion de leur type
        if (isset($_GET['gametype']) && $_GET['gametype'] ==$gameType->gametype) 
        {
    
            $Produits = (array) null;
            $reqProductlistType = $bdd->prepare("SELECT * FROM produit WHERE gametype='$gameType->gametype'");
            $reqProductlistType->execute();
            $data = $reqProductlistType->fetchAll(PDO::FETCH_OBJ);
            $Produits=$data;  
       
        }
          
  //   // $reqProductlistType = $bdd->prepare('SELECT * FROM gametype');
  //   // $reqGameType->execute();

   }//END foreach($GameTypes as $gameType)

/////////////////////////////////////////////////////////// FIN Block 3
 
///////////////////////////////////////////////////////////Block 4 

//Récupération du min age de chanque jeurx depuis la table "minage"


   $reqGameAge = $bdd->prepare('SELECT * FROM minage ORDER BY id DESC');
   $reqGameAge->execute();
  
   $GameAges= $reqGameAge->fetchAll(PDO::FETCH_OBJ);

    foreach($GameAges as $gameage){

            if (isset($_GET['minage']) && $_GET['minage'] == $gameage->minage) 
            {
                
                $Produits = (array) null;
                $reqProductlistAge = $bdd->prepare("SELECT * FROM produit WHERE minage='$gameage->minage'");
                $reqProductlistAge->execute();
                $data = $reqProductlistAge->fetchAll(PDO::FETCH_OBJ);
                $Produits=$data;  
                  
              } //End If  
    } //foreach($GameAges as $gameage)         
  

/////////////////////////////////////////////////////////// FIN Block 4    

/////////////////////////////////////////////////////////// Block 5   
//Identifier les users admin et préparer l'affichage avec la variable $ifadminuser=1  dans le menu le boutton admin
if(isset($_SESSION['user'])){
      
    $useremail=$_SESSION['user'];
     $reqsuser = $bdd->prepare('SELECT * FROM utilisateurs WHERE email = ? ' );
     $reqsuser->execute(array($useremail));
     $userinfo = $reqsuser->fetch();
     //echo $userinfo['admin'];
     $ifadminuser=$userinfo['admin'];
    }

/////////////////////////////////////////////////////////// FIN Block 5  






//De manière manuelle
//Filtrage des jeux en fcontion de leur type

//  if (isset($_GET['gametype']) && $_GET['gametype'] =="Actionadventure") 
//  {
    
//     $Produits = (array) null;
//     $reqProductlistType = $bdd->prepare("SELECT * FROM produit WHERE gametype='Actionadventure'");
//     $reqProductlistType->execute();
//     $data = $reqProductlistType->fetchAll(PDO::FETCH_OBJ);
//     $Produits=$data;  
       
//  }

//   if (isset($_GET['gametype']) && $_GET['gametype'] == "Survival" ) 
//  {
//     $Produits = array();
//     $reqProductlistType = $bdd->prepare("SELECT * FROM produit WHERE gametype='Survival'");
//     $reqProductlistType->execute();
//     $data = $reqProductlistType->fetchAll(PDO::FETCH_OBJ);
//     $Produits=$data;  
    
      
//  }

//  if (isset($_GET['gametype']) && $_GET['gametype'] == "action role-playing") 
//  {
//     $Produits = array();
//     $reqProductlistType = $bdd->prepare("SELECT * FROM produit WHERE gametype='action role-playing'");
//     $reqProductlistType->execute();
//     $data = $reqProductlistType->fetchAll(PDO::FETCH_OBJ);
//     $Produits=$data; 
        
//  }

  




?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>
    <header>
        <a href="index.php" class="logo">Ludothèque <span>MARIA</span></a>
        <div class="searchinput">
        <form action="Page-searched-jeux.php" method="GET">
            <input type="search" name="s" placeholder="Search for a game">
            <input type="submit" name="submit" class="button">
       </form>
       </div>
        <ul class="navigation">
                <li><a href="index.php">Home</a></li>
                <?php if(!empty($_SESSION['user'])) : ?>
                  <li><a href="profil.php">my account:<?php echo $_SESSION['user']?></a></li>
                  <li><a href="deconnexion.php">Log out</a></li>      
                  <?php else:?>
                   <li><a href="login.php">Log in</a></li> 
                <?php endif;?>
                <li><a href="cart2.php">my basket:(<span><?php echo $num_items_in_cart ?></span>)</a></li>
                <?php if(!empty($_SESSION['user']) && $ifadminuser==1) : ?>
                    <li><a href="Page-admin.php">Admin</a></li>
                <?php endif;?>
                      
                <a href="cart2.php?page=cart">
				        <!--<i class="fas fa-shopping-cart"></i> -->
               <!-- <span><?php //echo $num_items_in_cart ?></span>-->
				        </a>
        </ul>
    </header>
    <!--affichage de la page des jeux-->
    <main>
    <!-- <div class="recentlyadded" id="jeux">
        <h2>Recently Added Products</h2> -->
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
      <div class ="filter" align="center">
        <h1>Advanced search:</h1>
        <a href="page-jeux.php"><button type="button" class="button">All games</button></a>
          <?php foreach($GameTypes as $gameType): ?>              
                <a href="page-jeux.php?page=product&gametype=<?=$gameType->gametype?>"><button type="button" class="button"><?=$gameType->gametype?></button></a>
          <?php endforeach; ?>
          <?php foreach($GameAges as $gameage): ?>
                <a href="page-jeux.php?page=product&minage=<?=$gameage->minage?>"><button type="button" class="button"><?=$gameage->minage?></button></a>
          <?php endforeach; ?>

      </div>              
      
      <div class="products">               
        <?php foreach($Produits as $produit): ?> 
       
          <div class="product">
            <div class="producttitle">
              <h2><span class="name"><?= $produit->nom ?></span></h2>
            </div>
            <div class="productimage">
              <img src="images/<?= $produit->image?> "  width="300" height="300">
            </div>
            <div class="productdescription">
              <p class="card-text"><?= substr($produit->description, 0, 200); ?></p>
            </div>  
            <div class="productprice">  
              <span class="price"><?= $produit->prix ?> $</span>
            </div>  
              <a href="view_product.php?page=product&id=<?=$produit->id?>"  >  
              <button type="button" class="button">View</button>
              </a> 
              
          </div>
    
          <?php endforeach; ?>
       </div>


      

  </main>
    <script>
        document.querySelector('').style.display= "none"
    </script>
    <section class="pied-de-page" id="pied-de-page">
        <img src="image2.jpg" alt=""class="img-footer">
        <div class="texte-pied">
            <h1>Why should you choose us?</h1>
            <p>Our library helps you find and to choose whatever you want with only with one click</p>
        </div>
        <div class="pied">
            <div class="pied-g">
                <h1>Business hours</h1>
                <p><i class="fas fa-clock"></i> From Mondey till Friday- 8am till 8pm</p>
                <p><i class="fas fa-clock"></i> Saturday and Sunday-9am till 12pm</p>
            </div>
            <div class="pied-d">
                <h1>Our contact</h1>
                <p>Le Mans, France <i class="fas fa-map-marker-alt"></i></p>
                <p>contact@ludotheque.com <i class="fas fa-envelope-open-text"></i></p>
                <p>+33679**** <i class="fas fa-mobile-alt"></i></p>
            </div>
           
        </div>    
    </section>
 </body>
 </html>
 

    
    