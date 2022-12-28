<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

?>


<?php
require_once 'config.php';

//fonction qui affiche apres avoir ajouter des nouveaux utilisateurs

function afficherCheck()
{ {
        $bdd = new PDO("mysql:host=localhost;dbname=ludo;charset=utf8", "root", "");
    }


    $prodId = $_GET['id'];
    $req = $bdd->prepare("SELECT * FROM produit WHERE id = '$prodId'");
    $req->execute();
    $data = $req->fetchAll(PDO::FETCH_OBJ);

    return $data;

    $req->closeCursor();
}
$Produits = afficherCheck();
$total_products = $bdd->query("SELECT * FROM produit")->rowCount();

// Get the amount of items in the shopping cart, this will be displayed in the header.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

//Identifier les users admin et préparer l'affichage avec la variable $ifadminuser=1  dans le menu le boutton admin
if (isset($_SESSION['user'])) {

    $useremail = $_SESSION['user'];
    $reqsuser = $bdd->prepare('SELECT * FROM utilisateurs WHERE email = ? ');
    $reqsuser->execute(array($useremail));
    $userinfo = $reqsuser->fetch();
    $ifadminuser = $userinfo['admin'];
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styleViewProduct.css">
    <title>Shoping Cart</title>



</head>

<body>
    <header>
        <!-- <div class="content-wrapper"> -->
        <a href="#" class="logo">Ludothèque <span>MARIA</span></a>
        <!--pour le moteur de recherche-->
        <div class="searchinput">
            <form action="Page-searched-jeux.php" method="GET">
                <input type="search" name="s" placeholder="Search for a game">
                <input type="submit" name="submit" class="button">
            </form>
        </div>
        <ul class="navigation">
            <!-- affichage des menu en fonction de la connexion du client-->
            <?php if (!empty($_SESSION['user'])) : ?>
                <li><a href="page-jeux.php">Home</a></li>
                <li><a href="profil.php"><?php echo $_SESSION['user'] ?></a></li>
                <li><a href="deconnexion.php">Log out</a></li>
            <?php else : ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Log in</a></li>
            <?php endif; ?>
            <li><a href="cart2.php">my basket:(<span><?php echo $num_items_in_cart ?></span>)</a></li>
            <?php if (!empty($_SESSION['user']) && $ifadminuser == 1) : ?>
                <li><a href="Page-admin.php">Admin</a></li>
            <?php endif; ?>
            <a href="cart2.php?page=cart">
            </a>
            <div class="link-icons">
                <a href="cart2.php?page=cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span><?php echo $num_items_in_cart ?></span>
                </a>
            </div>

        </ul>
    </header>



    <main>

        <!--<div class="content-wrapper">-->



        <!--<div class="products-content-wrapper"> -->

        <div class="products-wrapper">

            <div class="divimage">
                <?php foreach ($Produits as $produit) : ?>
                    <img src="images/<?= $produit->image ?>" width="400" height="400" alt="<?= $produit->nom ?>">
            </div>
            <div class="divproduactdata">
                <h1 class="nom"><?= $produit->nom ?></h1><br>
                <span class="prix">
                    &dollar;<?= $produit->prix ?></span><br>
                <span class="description"><?= $produit->description ?></span><br>
                <form action="cart2.php?page=cart" method="post"><br>

                    <!-- vérifier que le produit est bien disponible en stock -->
                    <?php if ($produit->stockgame != 0) :   ?>
                        <!-- le terme quantity utilisé ici est celui du nombre que le client souhaite réserver -->
                        <input type="number" name="quantity" value="0" min="0" max="<?= $produit->stockgame ?>" placeholder="Quantity" required><br><br>
                        <input type="hidden" name="product_id" value="<?= $produit->id ?>">
                        <input type="submit" value="Add To Cart">
                    <?php else : ?>
                        <h5 style="color:red"> Out of stock </h5>
                    <?php endif; ?>

                </form>
                <!--</div>-->
            </div>
        <?php endforeach; ?>
        </div>

        <!--</div> -->
        <!--</div> -->

    </main>

</body>
<footer>
    <section class="pied-de-page" id="pied-de-page">
        <img src="image2.jpg" alt="" class="img-footer">
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
            <div class="pied-center">
                <h1>Our social media</h1>
                <i class="fab fa-facebook-square"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-twitter"></i>
            </div>
        </div>
    </section>
</footer>

</body>

</html>