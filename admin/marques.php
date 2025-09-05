<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header("LOCATION:index.php");
        exit();
    }
    require "../connexion.php";
    if(isset($_GET['delete']) && is_numeric($_GET['delete'])){
        $id = htmlspecialchars($_GET['delete']);

        $verif = $bdd->prepare("SELECT * FROM marque WHERE id=?");
        $verif->execute([$id]);
        $donVerif = $verif->fetch(PDO::FETCH_ASSOC);
        $verif->closeCursor();
        if(!$donVerif){
            header("LOCATION:marques.php");
            exit();
        }

        
        //supprmier les images des produits associés à la marque
        $deleteimg = $bdd->prepare("SELECT * FROM products WHERE marque=?");
        $deleteimg->execute([$id]);
        while($donDelImg = $deleteimg->fetch(PDO::FETCH_ASSOC)){
            unlink("../images/".$donDelImg['cover']);
            unlink("../images/mini_".$donDelImg['cover']);

            $imageAssoc = $bdd->prepare("SELECT * FROM images WHERE id_product=?");
            $imageAssoc->execute([$donDelImg['id']]);
            $donImgA = $imageAssoc->fetchAll(PDO::FETCH_ASSOC);
            foreach($donImgA as $imgA){
                unlink("../images/".$imgA['fichier']);
            }

            $delImgA = $bdd->prepare("DELETE FROM images WHERE id_product=?");
            $delImgA->execute([$donDelImg['id']]);
        }

        //suppression dans la bdd des produits associés à la marque
        $deleteProd = $bdd->prepare("DELETE FROM products WHERE marque=?");
        $deleteProd->execute([$id]);

        //suppression dans la bdd de la marque
        $delete = $bdd->prepare("DELETE FROM marque WHERE id=?");
        $delete->execute([$id]);
        
        header("LOCATION:marques.php?successDel=".$id);
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>

    <div class="container-fluid">
        <h1>Gestion des marques</h1>
        <a href="addMarque.php" class="btn btn-success my-3">Ajouter une marque</a>
        <?php
            if(isset($_GET['add'])){
                if($_GET['add'] == "success"){
                    echo "<div class='alert alert-success'>Vous avez bien ajouté une nouvelle marque à la base de données</div>";
                }
            }

            if(isset($_GET['successDel'])){
                echo "<div class='alert alert-warning'>Vous avez bien supprmier la marque n°".$_GET['successDel']." de la base de données</div>";
            }

            if(isset($_GET['update'])){
                echo "<div class='alert alert-warning'>Vous avez bien modifié la marque n°".$_GET['update']."</div>";
            }
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>nom</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $req = $bdd->query("SELECT * FROM marque ORDER BY id DESC");
                    while($don = $req->fetch(PDO::FETCH_ASSOC)){
                        echo "<tr>";
                            echo "<td>".$don['id']."</td>";
                            echo "<td>".$don['nom']."</td>";
                            echo "<td>";
                                echo "<a href='updateMarque.php?id=".$don['id']."' class='btn btn-warning'>Modifier</a>";
                                echo "<a href='marques.php?delete=".$don['id']."' class='btn btn-danger mx-2'>Supprimer</a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                    $req->closeCursor();
                ?>
            </tbody>
        </table>
    </div>

    <?php
        include("partials/footer.php");
    ?>
</body>
</html>