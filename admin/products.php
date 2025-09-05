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
        $verif = $bdd->prepare("SELECT * FROM products WHERE id=?");
        $verif->execute([$id]);
        $donV = $verif->fetch(PDO::FETCH_ASSOC);
        $verif->closeCursor();
        if(!$donV){
            header("LOCATION:products.php");
            exit();
        }

        //suppression des fichiers images de la galerie associer à un produit
        $delGal = $bdd->prepare("SELECT * FROM images WHERE id_product=?");
        $delGal->execute([$id]);
        $donDelGal = $delGal->fetchAll(PDO::FETCH_ASSOC);
        $delGal->closeCursor();
        //var_dump($donDelGal);
        foreach($donDelGal as $del){
            unlink("../images/".$del['fichier']);
        }

        //suppression de la galerie dans la bdd
        $delGalBdd = $bdd->prepare("DELETE FROM images WHERE id_product=?");
        $delGalBdd->execute([$id]);

        //suppression de la cover du produit
        unlink("../images/".$donV['cover']);
        unlink("../images/mini_".$donV['cover']);

        //suppression du produit dans la bdd
        $delete = $bdd->prepare("DELETE FROM products WHERE id=?");
        $delete->execute([$id]);
        header(("LOCATION:products.php?successDel=".$id));
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
    <title>Administration - produits</title>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>

    <div class="container-fluid">
        <h1>Gestion des produits</h1>
        <a href="addProduct.php" class="btn btn-success my-3">Ajouter un produit</a>
        <?php
            if(isset($_GET['add'])){
                if($_GET['add'] == "success"){
                    echo "<div class='alert alert-success'>Vous avez bien ajouté un nouveau produit à la base de données</div>";
                }
            }

            if(isset($_GET['successDel'])){
                echo "<div class='alert alert-warning'>Vous avez bien supprmier le produit n°".$_GET['successDel']." de la base de données</div>";
            }

            if(isset($_GET['update'])){
                echo "<div class='alert alert-warning'>Vous avez bien modifié le produit n°".$_GET['update']."</div>";
            }
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Marque</th>
                    <th>nom</th>
                    <th>Prix</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $req = $bdd->query("SELECT products.id AS pid, products.nom AS pnom, marque.nom AS mnom, products.prix AS prix FROM products INNER JOIN marque ON products.marque = marque.id");
                    while($don = $req->fetch(PDO::FETCH_ASSOC)){
                        echo "<tr>";
                            echo "<td>".$don['pid']."</td>";
                            echo "<td>".$don['mnom']."</td>";
                            echo "<td>".$don['pnom']."</td>";
                            echo "<td>".$don['prix']."</td>";
                            echo "<td>";
                                echo "<a href='updateProduct.php?id=".$don['pid']."' class='btn btn-warning'>Modifier</a>";
                                echo "<a href='products.php?delete=".$don['pid']."' class='btn btn-danger mx-2'>Supprimer</a>";
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