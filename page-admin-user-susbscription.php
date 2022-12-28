<?php 
    session_start(); // Démarrage de la session
    require_once 'config.php'; // On inclu la connexion à la bdd
    //Generation d'un random membership  number (N° adhérant)
    $memnb=rand(9999,9999999);

    // Si les variables existent et qu'elles ne sont pas vides
    if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_retype']))
    {
        // Patch XSS
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $password_retype = htmlspecialchars($_POST['password_retype']);
        $Address = htmlspecialchars($_POST['AdressPo']);
        //Variable qui prend deux valeurs '1' ou '0' pour distinguer un utilisateurs admin ou non.
        $ifadmin=0;
        if (isset($_POST['ifAdmin'])){
            $ifadmin=$_POST['ifAdmin'];
        }//fin if
        // On vérifie si l'utilisateur existe
        $check = $bdd->prepare("SELECT email FROM utilisateurs WHERE email = '$email' or membershipnb = '$memnb'");
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();

        $email = strtolower($email); // on transforme toute les lettres majuscule en minuscule pour éviter que Foo@gmail.com et foo@gmail.com soient deux compte différents ..
        
        // Si la requete renvoie un 0 alors l'utilisateur n'existe pas 
        if($row == 0){ 

            if(strlen($pseudo) <= 100){ // On verifie que la longueur du pseudo <= 100
                if(strlen($email) <= 100){ // On verifie que la longueur du mail <= 100
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Si l'email est de la bonne forme

                        if($password === $password_retype){ // si les deux mdp saisis sont bon

                            
                            // On insère dans la base de données
                
                            $requestuser = "INSERT INTO utilisateurs(pseudo,email,password,membershipnb,Address,admin) VALUES('$pseudo','$email','$password','$memnb','$Address','$ifadmin')";
                            $insert = $bdd ->prepare($requestuser);
                            $insert ->execute();
                            //$insert = $bdd->prepare('INSERT INTO utilisateurs(pseudo, email, password, token) VALUES($pseudo,$email,$password,"fbsdgbsdgbnsrnsrb")');
                            //$insert ->execute();       
                            // On redirige avec le message de succès
                            echo "<script>alert('user successfully added');window.location.href = 'page-admin-user-susbscription.php';</script>";
                            exit;
                            //header('Location:page-admin-user-susbscription.php');
                           
                        }else{ header('Location: inscription.php?reg_err=password'); die();}
                         }else{ header('Location: inscription.php?reg_err=email'); die();}
                }else{ header('Location: inscription.php?reg_err=email_length'); die();}
            }else{ header('Location: inscription.php?reg_err=pseudo_length'); die();}
        }else{ header('Location: inscription.php?reg_err=already'); die();}
    }

    //Vérifier que le user est connecté
 if(!isset($_SESSION['user'])){
      header('Location: login.php');
      //echo $_SESSION['user'];
    }

// Obtenez la quantité d'articles dans le panier.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;



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
        <a href="index.php" class="logo">Ludothèque <span>MARIA</span></a>
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
                 <a href="cart2.php?page=cart">
				</a>
        </ul>
    </header>

    <div class ="Adminfilter" align="center">
                 
        <a href="Page-admin.php"><button type="button" class="button" >Admin</button></a> 
      
          
    </div>   
                 
            <div align="center" class="FormAdminAccount">
                <h2> Add new client</h2>
                <br>
                <form method = "POST" action="page-admin-user-susbscription.php">
                    <h3><label>Pseudo:</label></h3>
                    <input type="text" name="pseudo" placeholder="Pseudo" required="required" /><br /><br/>
                    <h3><label>Email:</label></h3>
                    <input type="email" name="email" placeholder="Email" required="required" /><br /><br/>
                    <h3><label>Password:</label></h3>
                    <input type="password" name="password" placeholder="password" required="required"/><br /><br/>
                    <h3><label>re-type password:</label></h3>
                    <input type="password" name="password_retype" placeholder="re-type password" required="required"/><br /><br/>
                    <h3><label>Address:</label></h3>
                    <input type="text" name="AdressPo" placeholder="Address" required="required" value="Address" /><br /><br/>
                    <h3><label for="Admin">Admin</label><br></h3>
                    <input type="radio" id="admin" name="ifAdmin" value="1">
                    
                    <br/>
                    <br/>
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