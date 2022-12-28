<?php
session_start();
require_once 'config.php';

 //Vérifier que le user est connecté
 if(!isset($_SESSION['user'])){
      header('Location: login.php');
      //echo $_SESSION['user'];
    }

// Get the amount of items in the shopping cart.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

    //Récupération de l'historique de réservation d'un client
   //Récupération des information du user auprès de la DB
 if(isset($_SESSION['user']) )
 {//elle est fermé à la fin du la page.
     $useremail=$_SESSION['user'];
     $resuser = $bdd->prepare('SELECT * FROM utilisateurs WHERE email = ? ' );
     $resuser->execute(array($useremail));
     $userinfo = $resuser->fetch();
    //get info from booking table
    $bookinginfo = $bdd->prepare('SELECT booking.email, booking.date, booking.returndate, booking.quantityreserved, produit.id, produit.image, produit.nom, produit.prix FROM produit INNER JOIN booking ON produit.nom = booking.nom WHERE booking.email= ?');
     $bookinginfo->execute(array($useremail));
     $products = $bookinginfo->fetchAll(PDO::FETCH_ASSOC);
     
     //Les deux lignes suivantes sont pour le troubleshooting
    //  $row= $bookinginfo->rowCount();
    //  echo $row;

    
// Remove product from my DB

 if (isset($_GET['remove']) && is_numeric($_GET['remove']) && $_GET['removename'] && $_GET['removeproductquantity']) {
        $ReProductId = $_GET['remove'];
        $reProductName = $_GET['removename'];
        $reProductQuantity = $_GET['removeproductquantity'];
        //récupération de la valeur existante du "stockgame" du produit à supprimer dans la table "produit"
        $request1 = $bdd->prepare('SELECT * FROM produit WHERE id = ? ' );
        $request1->execute(array($ReProductId));
        $stockAvailable= $request1->fetch();
   //Calculer la nouvelle valeur du "stckogame"
        $newProductStockgame = $stockAvailable ['stockgame'] + $reProductQuantity ;
            //suppression de la ligne du produit à supprimer dans la table booking
            $useremail = htmlspecialchars($_SESSION['user']);
            $requestRemoveProductBookingTable = "DELETE FROM booking WHERE email='$useremail' AND nom='$reProductName'";
            $preparerequestRemoveProductBookingTable = $bdd ->prepare($requestRemoveProductBookingTable);
            $preparerequestRemoveProductBookingTable ->execute();
            //Pour tester
            $row = $preparerequestRemoveProductBookingTable ->rowCount();
            //echo $row;
             
        if ( $newProductStockgame <= $stockAvailable ['quantity'] ){
             //Mettre à joute la colone "stockgame" dans la table produit après que le client click sur "remove".
            $updateStockgameProduit = "UPDATE produit SET stockgame= $newProductStockgame WHERE id=$ReProductId";
            $prepareUpdateStockgameProduit = $bdd ->prepare($updateStockgameProduit);
            $prepareUpdateStockgameProduit ->execute();
            
        }
        //Rafraichir la page
        header('location: profil.php');
        


 }

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
    <link href="css/styleCart.css" rel="stylesheet" >
    <title>my account</title>
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
     <br>
     <br>
     <br>
     <br>
     <br>
     <br>

    <div align="center">
        <h1>my account <?php echo $userinfo['pseudo']; ?></h1>
        <br/><br/>
        Membership number=<?php echo $userinfo['membershipnb']; ?>
        <br/>
        Pseudo=<?php echo $userinfo['pseudo']; ?>
        <br/>
        Mail=<?php echo $userinfo['email']; ?>
        <br/>
        Your address=<?php echo $userinfo['Address']; ?>
        <br/>
        <br/>
        <br/>
        <a href="change-account.php" class="button">Change my profile</a>
    </div>

     <main>
    <div class="cart content-wrapper">
    <h1>History of reservation</h1>
    <form action="profil.php">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                    <td>date</td>
                    <td>Return date</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">You have no products added in your Shopping Cart</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                
                <tr>
                    <td class="img">
                        <a href="view_product.php?page=product&id=<?=$product['id']?>">
                            <img src="images/<?=$product['image']?>" width="50" height="50" alt="<?=$product['nom']?>">
                        </a>
                    </td>
                    <td>
                        <a href="view_product.php?page=product&id=<?=$product['id']?>"><?=$product['nom']?></a>
                        <br>
                        <a href="profil.php?page=profil&remove=<?=$product['id']?>&removename=<?=$product['nom']?>&removeproductquantity=<?=$product['quantityreserved']?>" class="remove">remove</a>
                        <!-- <a href="#" class="remove">remove</a> -->
                    </td>
                    <td class="price">&dollar;<?=$product['prix']?></td>
                    <td class="quantity"><?=$product['quantityreserved']?></td>
                    <td class="price">&dollar;<?=$product['prix'] * $product['quantityreserved']?></td>
                    <!-- réunitiliser la class "quantity pour l'affichage des dates" -->
                    <td class="quantity"><?=$product['date']?></td>
                    <td class="quantity"><?=$product['returndate']?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </form>

</div>

</main>
</body>

</html>
<?php
 }
?>