
<?php
session_start();
require_once 'config.php'; // On inclut la connexion à la base de données
if (isset($_POST['product_id']) && isset($_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $stmt = $bdd->prepare("SELECT * FROM produit WHERE id = $product_id");
    $stmt->execute();
    $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    $row= $stmt->rowCount();
    
    
    //echo $row;
    if ($row && $quantity > 0) {
        // Product exists in database, now we can create/update the session variable for the cart
        //la disponibilité du produit en stock est déjà traité dans view_product.php
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                // Product exists in cart so just update the quanity
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // Product is not in cart so add it
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // There are no products in cart, this will add the first product to cart
            $_SESSION['cart'] = array($product_id => $quantity);
           
        }
    }

   
}

// Remove product from cart, check for the URL param "remove", this is the product id, make sure it's a number and check if it's in the cart
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    // Remove the product from the shopping cart
    unset($_SESSION['cart'][$_GET['remove']]);
}

// Update product quantities in cart if the user clicks the "Update" button on the shopping cart page
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
    // Loop through the post data so we can update the quantities for every product in cart
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            // Always do checks and validation
            
            if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity > 0) {
                // Update new quantity
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    }
    // Prevent form resubmission...
    header('location: cart2.php?page=cart');
    exit;
}

// Check the session variable for products in cart
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;
// If there are products in cart
if ($products_in_cart) {
    
    // There are products in the cart so we need to select those products from the database
    // Products in cart array to question mark string array, we need the SQL statement to include IN (?,?,?,...etc)
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $bdd->prepare('SELECT * FROM produit WHERE id IN (' . $array_to_question_marks . ')');
    $stmt->execute(array_keys($products_in_cart));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $product) {
        $subtotal += (float)$product['prix'] * (int)$products_in_cart[$product['id']];
    }
}

// Get the amount of items in the shopping cart, this will be displayed in the header.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;


//Vider le Panier.
$num_items_in_cart = 0;
unset($_SESSION['cart']);


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
<html>
	<head>
		<meta charset="utf-8">
		<title>Ludothèque MARIA Cart</title>
        <link rel="stylesheet" href="css/style.css"> 
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
                  <li><a href="profil.php"><?php echo $_SESSION['user']?></a></li>
                  <li><a href="deconnexion.php">Log out</a></li>
                  <?php else:?>
                   <li><a href="login.php">Log in</a></li> 
                <?php endif;?>
                
                 <li><a href="cart2.php">my basket:(<span><?php echo $num_items_in_cart ?></span>)</a></li>
                 <?php if(!empty($_SESSION['user']) && $ifadminuser==1) : ?>
                    <li><a href="Page-admin.php">Admin</a></li>
                <?php endif;?>  
                <a href="cart2.php?page=cart">
				        <!--<i class="fas fa-shopping-cart"></i>
                        <span><?php //echo $num_items_in_cart ?></span>-->
				        </a>
        </ul>
        </header>
        
        <div class="reservationConfirmation">
                <br>
                <br>
                <br>
                <br>
                <h1 align="center">Reservation confirmed</h1>
                <br>
                <h3 align="center">Thank you for ordering with us, you can click here to view your history of reservation in details.</h3>
                <br>
                <div align ="center">
                <a href="profil.php" class="button">view my account</a>
                </div>
        </div>
    </body>
</html>