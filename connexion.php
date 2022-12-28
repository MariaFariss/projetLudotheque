<?php 
    session_start(); // Démarrage de la session
    require_once 'config.php'; // On inclut la connexion à la base de données
if(isset($_POST["formconnexion"]))
{    $email = htmlspecialchars($_POST['email']); 
     $password = htmlspecialchars($_POST['password']);
     //echo $email;   
    if(!empty($_POST['email']) && !empty($_POST['password'])) // S il existe les champs email, password et qu'il sont pas vident
    {
        // Patch XSS
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
             // On créer la session
            $_SESSION['user'] = $email;
           
            $req=$bdd->prepare('SELECT * FROM booking WHERE email= ?');

             $req->execute(array($email));
             $date = date('Y-m-d');
        // //recuperer les donnes sous forme des objets
                $Datas = $req->fetchAll(PDO::FETCH_OBJ);
                foreach($Datas as $data){
                        if ($data->returndate <= $date){
                        
                        //if ($data->returndate > $date){   //Pour tester que le produit est bein retiré de la table booking.      
                          //Date de retour est plus petite que la date courante (actuelle), donc on supprime ce produit de la table "booking"
      
                          $productname=$data->nom;
                          
                          //$productStockgame=$data->quantityreserved;
                          //récupération de la valeur existante du "stockgame" du produit à supprimer dans la table "produit"
                            $request1 = $bdd->prepare("SELECT * FROM produit WHERE id = '$productname'" );
                            $request1->execute();
                            // $stockAvailable= $request1->fetch();
                            $StockAvailables= $request1->fetchAll(PDO::FETCH_OBJ);
                            
                          //Calculer la nouvelle valeur du "stckogame"
                          //$newProductStockgame = $stockAvailable ['stockgame'] + $productStockgame ;
                          //delete product from the table booking après 1 mois:

                          $requestRemoveProductBookingTableAfter1Month = "DELETE FROM booking WHERE email='$data->email' AND nom='$data->nom'"; 
                          $execpreparerequestRemoveProductBookingTableAfter1Month = $bdd ->prepare($requestRemoveProductBookingTableAfter1Month);
                          $execpreparerequestRemoveProductBookingTableAfter1Month ->execute();
                            
                        //A finir Après  
                        //   //Delete from table produit
                        //   foreach($StockAvailables as $stockAvailable){
                        //         //Update a colomn "stockgame" de la table 'produit'
                        //            $stockAvailable2=$stockAvailable->stockgame;
                        //            $stockAvailablequantity= $stockAvailable->quantity;
                        //            $newProductStockgame = $stockAvailable2 + $productStockgame ; 
                        //             echo $stockAvailablequantity;
                        //             echo "<br>";
                        //             echo $newProductStockgame;
                        //             echo "<br>";
                        //             echo $stockAvailable2;//stockgame de la table produit
                        //             echo "<br>";
                        //             echo $productStockgame;//stockgame de la table booking


                        //             //  if ( $newProductStockgame < $stockAvailablequantity ){
                        //             //      $updateStockgameProduit = "UPDATE produit SET stockgame='$newProductStockgame' WHERE id='$productname'";
                        //             //      $prepareUpdateStockgameProduit = $bdd ->prepare($updateStockgameProduit);
                        //             //      $prepareUpdateStockgameProduit ->execute();
                        //             //  }
                        //    }//End Foreach                  
                          }//end If ($data->returndate > $date) 

                 }//end foreach($Datas as $data)
                 
           //  $Datas = $req->fetchAll(PDO::FETCH_OBJ);  
        //     $date = date('Y-m-d');
        //     // $diffDate = abs(strtotime($date2) - strtotime($date1));
                
        //     foreach($Datas as $data){
        //         if (strtotime($data->returndate) > strtotime($date)){
        //                  echo $data->returndate;

        //         }  
        //     } //fin foreach($Datas as $data)

            header('Location: page-jeux.php'); 
            $req->closeCursor();
	
            
            
        }else {
            $error = "Your Login Name or Password is invalid";
            $_SESSION["error"] = $error;
           header('Location: index.php');
           

        }
    }else{ header('Location: login.php'); die();} // si le formulaire est envoyé sans aucune données   

}    


?>
