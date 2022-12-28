<?php
session_start();
require_once 'config.php';

// Obtenez la quantité d'articles dans le panier.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

//Mettre à jour la DB quand toutes les 'required'  cases du form sont remplies

if ((!empty($_POST['newpseudo'])  && !empty($_POST['newpwd']) && !empty($_POST['re-newpwd']) && !empty($_POST['addresspo'])) and $_POST['newpwd'] == $_POST['re-newpwd']) {
    $newpseudo = $_POST['newpseudo'];
    $newpwd = $_POST['newpwd'];
    $renewpwd = $_POST['re-newpwd'];
    $email = $_SESSION['user'];
    $newaddress = $_POST['addresspo'];
    $updateuserprofile = "UPDATE utilisateurs SET pseudo='$newpseudo', password='$newpwd', Address='$newaddress' WHERE email='$email'";
    $prepareupdateuserprofile = $bdd->prepare($updateuserprofile);
    $prepareupdateuserprofile->execute();
    header('Location: profil.php');
}


//Identifier les users admin et préparer l'affichage avec la variable $ifadminuser=1  dans le menu le boutton admin
if (isset($_SESSION['user'])) {

    $useremail = $_SESSION['user'];
    $reqsuser = $bdd->prepare('SELECT * FROM utilisateurs WHERE email = ? ');
    $reqsuser->execute(array($useremail));
    $userinfo = $reqsuser->fetch();
    //echo $userinfo['admin'];
    $ifadminuser = $userinfo['admin'];
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
            <li><a href="#">Home</a></li>
            <?php if (!empty($_SESSION['user'])) : ?>
                <li><a href="page-jeux.php">my account:<?php echo $_SESSION['user'] ?></a></li>
                <li><a href="deconnexion.php">log out</a></li>
            <?php else : ?>
                <li><a href="login.php">Log in</a></li>
            <?php endif; ?>
            <li><a href="cart2.php">my basket:(<span><?php echo $num_items_in_cart ?></span>)</a></li>
            <?php if (!empty($_SESSION['user']) && $ifadminuser == 1) : ?>
                <li><a href="Page-admin.php">Admin</a></li>
            <?php endif; ?>
            <a href="cart2.php?page=cart">
            </a>
        </ul>
    </header>
    <div align="center" class="FormChangeAccount">
        <h2>Change my account</h2>
        <br>
        <form method="POST" action="change-account.php">
            <h3><label>Pseudo:</label></h3>
            <input type="text" name="newpseudo" placeholder="Pseudo" required="required" /><br /><br />
            <h3><label>Password:</label></h3>
            <input type="password" name="newpwd" placeholder="password" required="required" /><br /><br />
            <h3><label>re-type password:</label></h3>
            <input type="password" name="re-newpwd" placeholder="re-type password" required="required" /><br /><br />
            <h3><label>My address:</label></h3>
            <input type="text" name="addresspo" placeholder="Address" required="required" /><br /><br />
            <input type="submit" value="update" class="button" />
        </form>

    </div>
</body>

</html>