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
        $req = $bdd->prepare("SELECT * FROM products WHERE id=?");
        $req->execute([$id]);
        $don = $req->fetch((PDO::FETCH_ASSOC));
        $req->closeCursor();
        if(!$don){
            header("LOCATION:products.php");
            exit();
        }
    }else{
        header("LOCATION:products.php");
        exit();
    }
    //vérif de l'envoie du formulaire
    if(isset($_FILES['image'])){
        //verif valeurs
        //init error
        
        if($_FILES['image']['error'] == 0){
            $err=0;
            $dossier = '../images/';
            $fichier = basename($_FILES['image']['name']);
            $tailleMaxi = 2000000;
            $taille = filesize($_FILES['image']['tmp_name']);
            $extensions = ['.png', '.jpg', '.jpeg'];
            $extension = strrchr($_FILES['image']['name'], '.');

            if(!in_array($extension,$extensions))
            {
                $err=5;
            }

            if($taille>$tailleMaxi)
            {
                $err=6;
            }

            if($err == 0){
                //insert bdd
                $fichier = strtr($fichier, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
                $fichiercplt = rand().$fichier;

                if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier.$fichiercplt)){
                    //insertion dans bdd
                    $insert = $bdd->prepare("INSERT INTO images(fichier, id_product) VALUES(:fichier, :id_prod)");
                    $insert->execute([
                        ":fichier" => $fichiercplt,
                        ":id_prod" => $id
                    ]);
                    
                    header("LOCATION:updateProduct.php?id=".$id."&addimg=success");
                    exit();
                        //gestion redim
                        
                }else{
                    header("LOCATION:addImg.php?id=".$id."&errorimg=7");
                }
            }else{
                header("LOCATION:addImg.php?id=".$id."&errorimg=".$err);
            }
        }else{
            header("LOCATION:addImg.php?id=".$id."errorimg=".$_FILES['image']['error']);
            exit();
        }
    }else{
        header("LOCATION:addImg.php?id=".$id);
        exit();
    }

?>