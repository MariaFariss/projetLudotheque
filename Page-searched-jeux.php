<?php
 session_start(); 
 // Redirection si pas connecté

 require_once 'config.php';
           

//fonction qui affiche apres avoir ajouter des nouveaux utilisateurs
function afficherSearchResult()
{
 //s il arrive a se connecter
	
        require("connexionn.php");
        if(isset($_GET['s']) AND !empty($_GET['s'])){
            $recherche=htmlspecialchars($_GET['s']);
            $allproduct = $access->prepare("SELECT * FROM produit WHERE nom LIKE '%$recherche%' ORDER BY id DESC");
            $allproduct->execute();
            $data = $allproduct->fetchAll(PDO::FETCH_OBJ);
            $allproduct->closeCursor();

            $row=$allproduct->rowCount();
            if($row>0){
                return $data;
            }else{
                
                echo "<script>alert('No such product with the name wanted');window.location.href = 'index.php';</script>";
            }
        }else{
                
              echo "<script>alert('Please enter some thing');window.location.href = 'index.php';</script>";
            }
}
$Produits=afficherSearchResult();

// Obtenez la quantité d'articles dans le panier.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

//Identifier les users admin et préparer l'affichage avec la variable $ifadminuser=1  dans le menu le boutton admin
if(isset($_SESSION['user'])){
      
    $useremail=$_SESSION['user'];
     $reqsuser = $bdd->prepare('SELECT * FROM utilisateurs WHERE email = ? ' );
     $reqsuser->execute(array($useremail));
     $userinfo = $reqsuser->fetch();
     
     $ifadminuser=$userinfo['admin'];
    }

    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    
    <title>Searched result</title>  

</head>
<body>
    <header>
        <a href="index.php" class="logo">Ludothèque <span>MARIA</span></a>
         <!--pour le moteur de recherche-->
        <div class="searchinput">
        <form action="Page-searched-jeux.php" method="GET">
            <input type="search" name="s" placeholder="Search for a game">
            <input type="submit" name="submit" class="button">
       </form>
       </div>
        <ul class="navigation">
                <!-- affichage des menu en fonction de la connexion du client-->
                <?php if(!empty($_SESSION['user'])) : ?>
                  <li><a href="page-jeux.php">Home</a></li>
                  <li><a href="profil.php"><?php echo $_SESSION['user']?></a></li>
                  <li><a href="deconnexion.php">Log out</a></li>
                  <?php else:?>
                    <li><a href="index.php">Home</a></li>
                   <li><a href="login.php">Log in</a></li> 
                <?php endif;?>
                <li><a href="cart2.php">my basket:(<span><?php echo $num_items_in_cart ?></span>)</a></li>  
                <?php if(!empty($_SESSION['user']) && $ifadminuser==1) : ?>
                    <li><a href="Page-admin.php">Admin</a></li>
                <?php endif;?>  
                 <a href="cart2.php?page=cart">
				</a>
                <div class="link-icons">
                    <a href="cart2.php?page=cart">
						<i class="fas fa-shopping-cart"></i>
                        <span><?php //echo $num_items_in_cart ?></span>
					</a>
                </div>

        </ul>
    </header>
     <!--affichage de la page des jeux-->
     <main>
       <br>
        <br>
        <br>
        <br>
        <br>
        <br>
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
 </body>