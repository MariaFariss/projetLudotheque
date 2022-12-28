
<?php
session_start();
require_once 'config.php'; // On inclut la connexion à la base de données
// If the user clicked the add to cart button on the product page we can check for the form data
if (isset($_POST['product_id']) && isset($_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    // Set the post variables so we easily identify them, also make sure they are integer
    
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    // Prepare the SQL statement, we basically are checking if the product exists in our databaser
    $stmt = $bdd->prepare("SELECT * FROM produit WHERE id = $product_id");
    //$stmt->execute([$_POST['product_id']]);
    $stmt->execute();
    // Fetch the product from the database and return the result as an Array
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
// s'il y a un produit in the cart
if ($products_in_cart) {
    
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $bdd->prepare('SELECT * FROM produit WHERE id IN (' . $array_to_question_marks . ')');
    // We only need the array keys, not the values, the keys are the id's of the products
    $stmt->execute(array_keys($products_in_cart));
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Calculate the subtotal
    foreach ($products as $product) {
        $subtotal += (float)$product['prix'] * (int)$products_in_cart[$product['id']];
    }
}

// Get the amount of items in the shopping cart, this will be displayed in the header.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;



// Quand on click sur submit , on met à jour la DB puis on redirige vers la page place-order.php
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {    
    $email=$_SESSION['user'];
    $getuserinfobooking = $bdd->prepare("SELECT * FROM booking WHERE email ='$email' ");
    $getuserinfobooking->execute();
    $usernumberreservation = $getuserinfobooking->rowCount();
    //Check if the client has already 3 products in the bucket

     if (count($_SESSION['cart']) >= 4){
             echo "<script>alert('You have only 3 products to order');window.location.href = 'cart2.php';</script>";
             exit;
             
     }else{

    //Check if the client has already 3 product in the data base
    
        if ($usernumberreservation >= 3){
            echo "<script>alert('you already have already 3 games reserved in your profil. Please remove one or more.');window.location.href = 'cart2.php';</script>";
            exit;
         }else{

                foreach ($products as $product) {
                        $quantityreserved=$products_in_cart[$product['id']];
                        $email=$_SESSION['user'];
                        $stockgame=$product['stockgame'];
                        $productid=$product['id'];
                        $nom=$product['nom'];
                        $date = date('Y-m-d');
                        $returndate = date('Y-m-d', strtotime("+30 days"));
                        //Préparation de la quantité réelle en stock après réservation
                        $newstockgame= (int)$stockgame - (int)$quantityreserved;
                        if ($newstockgame>0){
                            //Ajouter la liste des jeux à réserver par le clients dans la table booking après que le client click sur le button "placeorder"
                            $Addinsinsidebooking = "INSERT INTO booking(email,nom,date,returndate,quantityreserved) VALUES('$email','$nom','$date','$returndate','$quantityreserved')";
                            $insertb = $bdd ->prepare($Addinsinsidebooking);
                            $insertb ->execute();

                            //Mettre à joute la colone "stockgame" dans la table produit.
                            $updateproduit = "UPDATE produit SET stockgame= $newstockgame WHERE id=$productid";
                            $insertp = $bdd ->prepare($updateproduit);
                            $insertp ->execute();
                        }else{
                            //On revérifier que le jeux est touours disponible entre le moment de son ajout dans le panier et le moment de la réservation (clisk sur le bouton "placeorder" )
                            //Il se peut que le jeux ne soit pas disponible entre le moment de son ajouté dans le panier et le moment de sa réservation.
                            echo "<script>alert('Impossible to reserve. Game is out of stock');window.location.href = 'index.php';</script>";
                        }

                    }
                    //après la mise à jour des table booking et produit, on fait la redirection vers la page de confirmation de réservation.
                    header('Location: place-order.php?page=placeorder');
                }//END Else
        }//END else
    

    
}

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
<html>
	<head>
		<meta charset="utf-8">
		<title>Ludothèque MARIA Cart</title>
		<link href="css/styleCart.css" rel="stylesheet" >
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
                <li><a href="page-jeux.php">Product</a></li>
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
    <main>
    <div class="cart content-wrapper">            
    <h1>Shopping Cart</h1>
    <form action="cart2.php?page=cart" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
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
                        <a href="cart2.php?page=cart&remove=<?=$product['id']?>" class="remove">Remove</a>
                    </td>
                    <td class="price">&dollar;<?=$product['prix']?></td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$product['id']?>" value="<?=$products_in_cart[$product['id']]?>" min="1" max="<?=$product['stockgame']?>" placeholder="Quantity" required>
                    </td>
                    <td class="price">&dollar;<?=$product['prix'] * $products_in_cart[$product['id']]?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal</span>
            <span class="price">&dollar;<?=$subtotal?></span>
        </div>
        <div class="buttons">
            <input type="submit" value="Update" name="update">
            <input type="submit" value="Place Order" name="placeorder">
        </div> 
    </form>
        <div class="buttons">
            <!-- <input type="submit" value="Place Order" name="placeorder">  -->
            <!-- <a href="place-order.php"><input type="submit" value="Place Order" name="placeorder"></a> -->
        </div>
</div>

</main>
        
    </body>
</html>