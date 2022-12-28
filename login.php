<?php 
    session_start(); // Démarrage de la session
    require_once 'config.php'; // On inclut la connexion à la base de données
if(isset($_POST["formconnexion"]))
{    $email = htmlspecialchars($_POST['email']); 
     $password = htmlspecialchars($_POST['password']);
     echo $email;   
    if(!empty($_POST['email']) && !empty($_POST['password'])) // S il existe les champs email, password et qu'il sont pas vident
    {
       
        $email = htmlspecialchars($_POST['email']); 
        $password = htmlspecialchars($_POST['password']);
        
        $email = strtolower($email); // email transformé en minuscule
        
        // On regarde si l'utilisateur est inscrit dans la table utilisateurs
        $check = $bdd->prepare("SELECT email FROM utilisateurs WHERE email = '$email' and password = '$password'");
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();

        

        // Si > à 0 alors l'utilisateur existe
        if($row == 1)
        {
             // On créer la session et on redirige sur page-jeux.php
            //$_SESSION['pseudo'] = $data['token'];
            $_SESSION['user'] = $email;
            //$_SESSION['id'] = $_POST['id'];
            header('Location: page-jeux.php');
          
		    $req=$bdd->prepare("SELECT * FROM booking WHERE email=$email ORDER BY id DESC");

            $req->execute();
        //recuperer les donnes sous forme des objets
            $Datas = $req->fetchAll(PDO::FETCH_OBJ);  
            $date = date('Y-m-d');
                
            foreach($Datas as $data){
                echo $data->returndate;
            
                    if ($data->returndate > $date){
                         $deleteproductBooking= $bdd->prepare("DELETE FROM booking WHERE email='$email' AND nom='$data->nom'");
                         $deleteproductBooking ->execute();

                }  
            }  
            
            
            
        }else {
            $error = "Your Login Name or Password is invalid";
            $_SESSION["error"] = $error;
           header('Location: index.php');
           

        }
    }else{ header('Location: login.php'); die();} // si le formulaire est envoyé sans aucune données   

}    


?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="author" content="NoS1gnal"/>
            <link rel="stylesheet" href="css/style.css">
            <title>Log in</title>
        </head>
        <body>
        <header>
        <a href="index.php" class="logo">Ludothèque <span>MARIA</span></a>
                 
       <!-- pour les boutton dans le header -->
        <ul class="navigation">
                <li><a href="index.php">Home</a></li>
               
        </ul>
        </header>
        <div class="login-form">
             <?php 
                if(isset($_GET['login_err']))
                {
                    $err = htmlspecialchars($_GET['login_err']);

                    switch($err)
                    {
                        case 'password':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Error</strong> wrong password
                            </div>
                        <?php
                        break;

                        case 'email':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Error</strong> wrong e-mail
                            </div>
                        <?php
                        break;

                        case 'already':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Error</strong> inexistant account
                            </div>
                        <?php
                        break;
                    }
                }
                ?> 
            
            <form action="connexion.php" method="post">
                <h2 class="text-center">Log in</h2>       
                <div class="form-login">
                    <input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
                </div>
                <div class="form-login">
                    <input type="password" name="password" class="form-control" placeholder="password" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <br/>
                    <button type="submit" name="formconnexion" class="button">Log in</button>

                    <?php
                    if(isset($_SESSION["error"])){
                        $error = $_SESSION["error"];
                        echo "<span>$error</span>";
                    }
                ?>  
                </div>   
                

            </form>
          
            <div class="">
            <a href="inscription.php"><button type="submit" name="formconnexion" class="button">Registration</button></a>
            </div>                
        </div>
        <style>
            .login-form {
                width: 340px;
                margin: 200px auto;
                text-align: center;
            }
            .login-form form {
                margin-bottom: 15px;
                background: #f7f7f7;
                box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
                padding: 30px;
            }
            .login-form h2 {
                margin: 0 0 15px;
            }
            .form-control, .btn {
                min-height: 38px;
                border-radius: 2px;
            }
            .btn {        
                font-size: 15px;
                font-weight: bold;
            }
        </style>
        </body>
</html>
<?php
    unset($_SESSION["error"]);
?>