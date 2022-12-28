<?php

    try 
    {
        $server = "sql306.epizy.com";
        $user = "epiz_33272938";
        $password = "sFqAOaxSkXQhj";
        $database = "epiz_33272938_ludo";
        $bdd = new PDO("mysql:host=".$server.";dbname=".$database.";charset=utf8", $user, $password);
     
    }
    //s il y ;a une erreur
    catch(PDOException $e)
    {
        die('Erreur : '.$e->getMessage());
    }
?>