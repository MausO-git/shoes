<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header("LOCATION:index.php");
        exit();
    }


    require "../connexion.php";
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = htmlspecialchars($_GET['id']);
        $req = $bdd->prepare("SELECT * FROM marque WHERE id=?");
        $req->execute([$id]);
        $don = $req->fetch((PDO::FETCH_ASSOC));
        $req->closeCursor();
        if(!$don){
            header("LOCATION:marques.php");
            exit();
        }
    }else{
        header("LOCATION:marques.php");
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
            $update = $bdd->prepare("UPDATE marque SET nom=:nom WHERE id=:id");
            $update->execute(([
                "id" => $id,
                ":nom" => $nom
            ]));
            header("LOCATION:marques.php?update=".$id);
            exit();
        }else{
            header("LOCATION:updateMarque.php?id=".$id."&error=".$err);
            exit();
        }
    }else{
        header("LOCATION:marques.php");
        exit();
    }


?>