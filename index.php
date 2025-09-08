<?php
        require "connexion.php";

        $tabMenu = [
            "home" => "home.php",
            "last" => "last.php",
            "all" => "all.php",
            "modele" => "modele.php"
        ];
        
        if(isset($_GET['action'])){
            if(array_key_exists($_GET['action'], $tabMenu)){
                $menu = $tabMenu[$_GET['action']];
                if($_GET['action'] == "modele"){
                    if(isset($_GET['id']) && is_numeric($_GET['id'])){
                        $id = htmlspecialchars($_GET['id']);
                        $modReq = $bdd->prepare("SELECT p.nom AS nom, p.description AS descr, m.nom AS nMarque, p.prix AS prix, p.cover AS cover FROM products p INNER JOIN marque m ON p.marque=m.id WHERE p.id=?");
                        $modReq->execute([$id]);
                        $donMod = $modReq->fetch(PDO::FETCH_ASSOC);
                        $modReq->closeCursor();
                        if(!$donMod){
                            header("LOCATION:404.php");
                            exit();
                        }
                        $menu = $tabMenu[$_GET['action']];
                    }else{
                        header("LOCATION:404.php");
                        exit;
                    }
                }elseif($_GET['action'] == "all"){
                    //pagination
                    $reqCount = $bdd->query("SELECT * FROM products");
                    $count = $reqCount->rowCount();
                    //la limite
                    $limit = 3;
                    $nbPage = ceil($count/$limit);

                    if(isset($_GET['page']) && is_numeric($_GET['page'])){
                        $pg = htmlspecialchars($_GET['page']);
                    }else{
                        //pas eu de pagination
                        $pg = 1;
                    }

                    $offset = ($pg - 1) * $limit;
                }else{
                    $menu = $tabMenu[$_GET['action']];
                }
            }else{
                header("LOCATION:404.php");
                exit();
            }
        }else{
            $menu = "last.php";
        }
    ?>
<!DOCTYPE html>
<html lang="en">
    <?php include("partials/head.php") ?>
<body>
    <?php include("partials/header.php") ?>
    <h1 class="m-4">Shoes</h1>
    <?php include("pages/".$menu) ?>
    <?php include("partials/footer.php") ?>
</body>
</html>