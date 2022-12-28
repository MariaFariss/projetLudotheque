<?php
session_start();
require_once 'config.php';

//  //Vérifier que le user n'est pas connecté
//  if(!isset($_SESSION['user'])){
//      //Ici le user n'est pas connecté
//       header('Location: login.php');
//       //echo $_SESSION['user'];
//     }

// Obtenez la quantité d'articles dans le panier.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;




//Insertion  dans  la table "produits" les nouveaux produits par l'admin
if(!empty($_POST['image']) && !empty($_POST['nom']) && !empty($_POST['prix']) && !empty($_POST['description']) && !empty($_POST['quantity']) && !empty($_POST['gametype']) && !empty($_POST['minage']))
    {
   $newimagegame= $_POST['image'];
   $newnomgame=$_POST['nom'];
   $pricegame=$_POST['prix'];
   $descriptiongame=$_POST['description'];
   $quantitygame=$_POST['quantity'];
   $stockgame=$_POST['quantity'];
   $gametype=$_POST['gametype'];
   $minage=$_POST['minage'];     
   
   $requestInsertGame = "INSERT INTO produit(image,nom,prix,description,quantity,stockgame,gametype,minage) VALUES('$newimagegame','$newnomgame','$pricegame','$descriptiongame','$quantitygame','$stockgame','$gametype','$minage')";
   
   $execrequestInsertGame = $bdd ->prepare($requestInsertGame);
   $execrequestInsertGame ->execute();

}//Fin IF

//Identifier les users admin et préparer l'affichage avec la variable $ifadminuser=1  dans le menu le boutton admin
if(isset($_SESSION['user'])){
      
    $useremail=$_SESSION['user'];
     $reqsuser = $bdd->prepare('SELECT * FROM utilisateurs WHERE email = ? ' );
     $reqsuser->execute(array($useremail));
     $userinfo = $reqsuser->fetch();
     //echo $userinfo['admin'];
     $ifadminuser=$userinfo['admin'];
    }else{
        //Vérifier que le user n'est pas connecté
        //Ici le user n'est pas connecté
        header('Location: login.php');
    }
    

     ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>my account</title>
</head>

<body>
      <header>
        <a href="#" class="logo">Ludothèque <span>MARIA</span></a>
         <!--pour le moteur de recherche-->
        <div class="searchinput">
        <form action="Page-searched-jeux.php" method="GET">
            <input type="search" name="s" placeholder="Search for a game">
            <input type="submit" name="submit" class="button">
       </form>
       </div>
       <!-- pour les boutton dans le header -->
        <ul class="navigation">
                <li><a href="index.php">Home</a></li>
                <?php if(!empty($_SESSION['user'])) : ?>
                  <li><a href="page-jeux.php">my account:<?php echo $_SESSION['user']?></a></li>
                  <li><a href="deconnexion.php">log out</a></li>
                <?php else:?>
                   <li><a href="login.php">Log in</a></li>   
                <?php endif;?>
                <li><a href="cart2.php">my basket:(<span><?php echo $num_items_in_cart ?></span>)</a></li>   
                <?php if(!empty($_SESSION['user']) && $ifadminuser==1) : ?>
                    <li><a href="Page-admin.php">Admin</a></li>
                <?php endif;?>  
                 <a href="cart2.php?page=cart">
				</a>
        </ul>
    </header>

    <div class ="Adminfilter" align="center">
                 
        <a href="page-admin-user-susbscription.php"><button type="button" class="button" >Add client profil</button></a> 
        <a href="Page-admin.php"><button type="button" class="button">Admin</button></a>
          
    </div>   
                 
            <div align="center" class="FormAdminAccount">
                <h2> Add new game</h2>
                <br>
                <form method = "POST" action="page-admin-Add-games.php">
                    <h3><label>Image of the game:</label></h3>
                    <input type="text" name="image" placeholder="image name" required="required" /><br /><br/>
                    <h3><label>Name:</label></h3>
                    <input type="text" name="nom" placeholder="Name" required="required" /><br /><br/>
                    <h3><label>Price:</label></h3>
                    <input type="number" name="prix" placeholder="Price" required="required"/><br /><br/>
                    <h3><label>Description:</label></h3>
                    <input type="text" name="description" placeholder="Description" required="required"/><br /><br/>
                    <h3><label>Quantity:</label></h3>
                    <input type="number" name="quantity" placeholder="Quantity" required="required" value="Address" /><br /><br/>
                    <!-- <h3><label>Stock game:</label></h3>
                    <input type="number" name="stockgame" placeholder="Stock game" value="0" /><br /><br/> -->
                    <h3><label>Game type:</label></h3>
                    <input type="text" name="gametype" placeholder="Game type" required="required"/><br /><br/>
                    <h3><label>Min Age:</label></h3>
                    <input type="number" name="minage" placeholder="Address" required="required" /><br /><br/>
                    <!-- Button Add -->
                    <input type="submit" value="Add" class="button" />
                </form> 
            
             </div>
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
