<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header("LOCATION:index.php");
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
        <h1>Ajouter un produit</h1>
        <a href="products.php" class="btn btn-secondary my-3">Retour</a>
        <?php
            if(isset($_GET['error'])){
                echo "<div class='alert alert-danger'>Une erreur est survenue (code erreur: ".$_GET['error'].")</div>";
            }
        ?>
        <form action="treatmentAddProduct.php" method="POST" enctype="multipart/form-data">
            <div class="form-group my-2">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" class="form-control">
            </div>
            <div class="form-group my-2">
                <label for="marque">Marque</label>
                <select name="marque" id="marque" class="form-control">
                    <?php
                        require "../connexion.php";
                        $marques = $bdd->query("SELECT * FROM marque");
                        while($donMarque = $marques->fetch((PDO::FETCH_ASSOC))){
                            echo "<option value ='".$donMarque['id']."'>".$donMarque['nom']."</option>";
                        }
                        $marques->closeCursor();
                    ?>
                </select>
            </div>
            <div class="form-group my-2">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="form-group my-2">
                <label for="image">Image de couverture</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <div class="form-group my-2">
                <label for="price">Prix</label>
                <input type="number" name="price" id="price" step="0.01" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Ajouter" class="btn btn-primary">
            </div>
        </form>
        
    </div>

    <?php
        include("partials/footer.php");
    ?>
</body>
</html>