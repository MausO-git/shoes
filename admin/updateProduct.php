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

    if(isset($_GET['delete']) && is_numeric($_GET['delete'])){
        $deleteId = htmlspecialchars($_GET['delete']);
        $verifImg = $bdd->prepare("SELECT * FROM images WHERE id=?");
        $verifImg->execute([$deleteId]);
        $donImg = $verifImg->fetch(PDO::FETCH_ASSOC);
        $verifImg->closeCursor();
        if(!$donImg){
            header("LOCATION:updateProduct.php?id=".$id);
            exit();
        }

        unlink("../images/".$donImg['fichier']);

        $delete = $bdd->prepare("DELETE FROM images WHERE id=?");
        $delete->execute([$deleteId]);

        header("LOCATION:updateProduct.php?id=".$id."&deletesuccess=".$deleteId);
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
    <title>Administration - ajout de produit</title>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="container-fluid">
                    <h1>Modifier un produit</h1>
                    <a href="products.php" class="btn btn-secondary my-3">Retour</a>
                    <?php
                        if(isset($_GET['error'])){
                            echo "<div class='alert alert-danger'>Une erreur est survenue (code erreur: ".$_GET['error'].")</div>";
                        }
                    ?>
                    <form action="treatmentUpdateProduct.php?id=<?= $don['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group my-2">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-control" value="<?= $don['nom'] ?>">
                        </div>
                        <div class="form-group my-2">
                            <label for="marque">Marque</label>
                            <select name="marque" id="marque" class="form-control">
                                <?php
                                    $marques = $bdd->query("SELECT * FROM marque");
                                    while($donMarque = $marques->fetch((PDO::FETCH_ASSOC))){
                                        if($don['marque'] == $donMarque['id']){
                                            echo '<option value="'.$donMarque['id'].'" selected>'.$donMarque['nom'].'</option>';
                                        }else{
                                            echo '<option value="'.$donMarque['id'].'">'.$donMarque['nom'].'</option>';
                                        }
                                    }
                                    $marques->closeCursor();
                                ?>
                            </select>
                        </div>
                        <div class="form-group my-2">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"><?= $don['description'] ?></textarea>
                        </div>
                        <div class="form-group my-2">
                            <?php
                                echo "<div class='col-5'><img src='../images/mini_".$don['cover']."' alt='image' class='img-fluid'></div>"
                            ?>
                            <label for="image">Image de couverture</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>
                        <div class="form-group my-2">
                            <label for="price">Prix</label>
                            <input type="number" name="price" id="price" step="0.01" class="form-control" value="<?= $don['prix'] ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Modifier" class="btn btn-warning">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <h1>Gestion galerie image</h1>
                <a href="addImg.php?id=<?= $id ?>" class="btn btn-success my-2">Ajouter une image</a>
                <?php
                    if(isset($_GET['addimg'])){
                        echo "<div class='alert alert-success'>Vous avez bien ajouté une image à la galerie</div>";
                    }
                    if(isset($_GET['deletesuccess'])){
                        echo "<div class='alert alert-danger'>Vous avez bien supprimé l'image n°".$_GET['deletesuccess']." de la galerie</div>";
                    }
                ?>
                <table class="table table-striped">
                    <thead>
                        <th>id</th>
                        <th>image</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                            $images = $bdd->prepare("SELECT * FROM images WHERE id_product=? ORDER BY id DESC");
                            $images->execute([$id]);
                            $donImages = $images->fetchAll(PDO::FETCH_ASSOC);
                            $images->closeCursor();
                            foreach($donImages as $image){
                                echo "<tr>";
                                    echo "<td>".$image['id']."</td>";
                                    echo "<td><img src='../images/".$image['fichier']."' alt='image product' class='col-3'></td>";
                                    echo "<td>";
                                        echo "<a href='updateProduct.php?id=".$id."&delete=".$image['id']."' class='btn btn-danger'>Supprimer</a>";
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

    <?php
        include("partials/footer.php");
    ?>
</body>
</html>