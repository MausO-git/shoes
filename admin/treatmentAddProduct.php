<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header("LOCATION:index.php");
        exit();
    }
    require "../connexion.php";
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

        if(empty($_POST['marque']) || !is_numeric($_POST['marque'])){
            $err = 2;
        }else{
            $marque = htmlspecialchars($_POST['marque']);
            $vm = $bdd->prepare("SELECT * FROM marque WHERE id=?");
            $vm->execute([$marque]);
            if(!$don = $vm->fetch()){
                $err = 3;
            }
        }

        if(empty($_POST['description'])){
            $err = 4;
        }else{
            $descr = htmlspecialchars($_POST['description']);
        }

        

        if(empty($_POST['price'])){
            $err = 5;
        }else{
            $prix = htmlspecialchars($_POST['price']);
        }

        if($err == 0){
            if($_FILES['image']['error'] == 0){
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
                        $insert = $bdd->prepare("INSERT INTO products(nom, marque, description, cover, prix ) VALUES(:nom, :marque, :descr, :cover, :prix)");
                        $insert->execute([
                            ":nom" => $nom,
                            ":marque" => $marque,
                            ":descr" => $descr,
                            ":cover" => $fichiercplt,
                            ":prix" => $prix
                        ]);

                        //gestion redim
                        if($extension == ".jpg" || $extension == ".jpeg"){
                            header("LOCATION:redim.php?image=".$fichiercplt);
                            exit();
                        }else{
                            //cas fichier png
                            header("LOCATION:redimpng.php?image=".$fichiercplt);
                            exit();
                        }
                    }else{
                        header("LOCATION:addProduct.php?errorimg=7");
                    }
                }else{
                    header("LOCATION:addProduct.php?errorimg=".$err);
                }
            }else{
                header("LOCATION:addProduct.php?errorimg=".$_FILES['image']['error']);
            }
        }else{
            header(("LOCATION:addProduct.php?error=".$err));
            exit();
        }
        
    }else{
        header("LOCATION:products.php");
        exit();
    }

?>