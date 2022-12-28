<?php
session_start();
    require_once 'config.php';


//fonction qui affiche apres avoir ajouter des nouveaux utilisateurs
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

// Obtenez la quantité d'articles dans le panier.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

//Identifier les users admin et préparer l'affichage avec la variable $ifadminuser=1  dans le menu le boutton admin
if(isset($_SESSION['user'])){
      
    $useremail=$_SESSION['user'];
     $reqsuser = $bdd->prepare('SELECT * FROM utilisateurs WHERE email = ? ' );
     $reqsuser->execute(array($useremail));
     $userinfo = $reqsuser->fetch();
     //echo $userinfo['admin'];
     $ifadminuser=$userinfo['admin'];
    }


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <link rel="stylesheet" href="css/style.css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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
                <li><a href="#">Home</a></li>
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
                <a href="login.php" class="reservation">Reservation</a>   
                 <a href="cart2.php?page=cart">
				</a>
        </ul>
    </header>
    <section class="baniere" id="baniere">
        <div class="banier-text">
            <br>
            <br>
            <br>
            <br>
            <h1>Ludothéque pour tous</h1>
            <p>Welcome to our Ludotheque</p>
        </div>
        <div>
            <div class="baniere_btn">
                <a href="#jeux"> <span></span> Let's Play</a>
                
            </div>
        </div>
    </section>
     <!--affichage de la page des jeux-->
<main>
    <!-- <div class="recentlyadded" id="jeux">
        <h2>Recently Added Products</h2> -->
    <div class="products" id=jeux>

      <?php foreach($Produits as $produit): ?> 
        <div class="product">
            <div class="producttitle">
              <h2><span class="name"><?= $produit->nom ?></span></h2>
            </div>
            <div class="productimage">
              <img src="images/<?= $produit->image?>"  width="300" height="300">
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

      <!-- </div> -->
   

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