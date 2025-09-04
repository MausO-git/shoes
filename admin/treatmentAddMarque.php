<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header("LOCATION:index.php");
        exit();
    }

    //vérif de l'envoie du formulaire
    if(isset($_POST['nom'])){
        //verif valeurs
        //init error
        $err = 0;
        if(empty($_POST['nom'])){
            $err = 1;
        }else{
            $nom = htmlspecialchars($_POST['nom']);
        }

        //verif etat de l'error
        if($err == 0){
            //pas d'erreur => insertion bdd
            require "../connexion.php";
            $insert = $bdd->prepare("INSERT INTO marque(nom) VALUES (:nom)");
            $insert->execute(([
                ":nom" => $nom
            ]));
            header("LOCATION:marques.php?add=success");
            exit();
        }else{
            header("LOCATION:addMarque.php?error=".$err);
            exit();
        }
    }else{
        header("LOCATION:marques.php");
        exit();
    }

?>