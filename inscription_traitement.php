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

                            // On hash le mot de passe avec Bcrypt, via un coût de 12 (complexité de cryptage = 12)
                            //To check later how to work.
                           //$cost = ['cost' => 12];
                            //$password = password_hash($password, PASSWORD_BCRYPT, $cost);
                            
                            // On stock l'adresse IP
                            // Si on laisse le stackage de l'@IP  de chaque utilisateur, cela permetra un check lors de la connexion de l utilisateur
                            //De ce fait, il faut dans le script connexion faire un check d'IP et alerter l utilisateur  qu il se connecte sur une autre @IP
                            // que la précédante et lui demander de confirmer s il est le bon utilisateur. Aspect de sécurité.

                          //  $ip = $_SERVER['REMOTE_ADDR']; 

                            // On insère dans la base de données
                
                            $requestuser = "INSERT INTO utilisateurs(pseudo,email,password,membershipnb,Address,admin) VALUES('$pseudo','$email','$password','$memnb','$Address','0')";
                            $insert = $bdd ->prepare($requestuser);
                            $insert ->execute();
                            //$insert = $bdd->prepare('INSERT INTO utilisateurs(pseudo, email, password, token) VALUES($pseudo,$email,$password,"fbsdgbsdgbnsrnsrb")');
                            //$insert ->execute();       
                            // On redirige avec le message de succès
                            header('Location:page-jeux.php');
                           
                        }else{ header('Location: inscription.php?reg_err=password'); die();}
                         }else{ header('Location: inscription.php?reg_err=email'); die();}
                }else{ header('Location: inscription.php?reg_err=email_length'); die();}
            }else{ header('Location: inscription.php?reg_err=pseudo_length'); die();}
        }else{ header('Location: inscription.php?reg_err=already'); die();}
    }

    ?>