<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="author" content="NoS1gnal"/>
            <link rel="stylesheet" href="css/style.css">
            <title>Registration</title>
        </head>
        <body>
        <header>
        <a href="index.php" class="logo">Ludothèque <span>MARIA</span></a>
                 
       <!-- pour les boutton dans le header -->
        <ul class="navigation">
            <li><a href="index.php">Home</a></li>
               
        </ul>
        </header>
        <div class="login-form" align="center">
            <?php 
                if(isset($_GET['reg_err']))
                {
                    $err = htmlspecialchars($_GET['reg_err']);

                    switch($err)
                    {
                        case 'success':
                        ?>
                            <div class="alert alert-success">
                                <strong>Succès</strong> inscription réussie !
                            </div>
                        <?php
                        break;

                        case 'password':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> mot de passe différent
                            </div>
                        <?php
                        break;

                        case 'email':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> email non valide
                            </div>
                        <?php
                        break;

                        case 'email_length':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> email trop long
                            </div>
                        <?php 
                        break;

                        case 'pseudo_length':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> pseudo trop long
                            </div>
                        <?php 
                        case 'already':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> compte deja existant
                            </div>
                        <?php 

                    }
                }
                ?>
            
            <form action="inscription_traitement.php" method="post">
                <h2 class="text-center">Registration</h2>       
                        <div class="form-inscription">
                        <input type="text" name="pseudo" class="form-control" placeholder="Pseudo" required="required" autocomplete="off">
                        </div>
                        <div class="form-inscription">
                        <input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
                        </div>
                        <div class="form-inscription">
                        <input type="text" name="AdressPo" class="form-control" placeholder="Address" value="Address" autocomplete="off">
                         </div>
                         <div class="form-inscription">
                        <input type="password" name="password" class="form-control" placeholder="pasword" required="required" autocomplete="off">
                        </div>
                        <div class="form-inscription">
                        <input type="password" name="password_retype" class="form-control" placeholder="Repassword" required="required" autocomplete="off">
                        </div>

                        <div class="">
                        <br/>
                        <br/>    
                        <button type="submit" class="button">Registration</button>
                        </div> 
                </div>      
            </form>
        <style>
            .login-form {
                width: 340px;
                margin: 200px auto;
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