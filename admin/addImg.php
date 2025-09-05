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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Administration - ajout d'image'</title>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <div class="container-fluid">
        <h1>Ajouter une image pour le produit <?= $don['nom'] ?></h1>
        <?php
            if(isset($_GET['errorimg'])){
                echo "<div class='alert alert-danger'>Une erreur est survenue (code d'erreur : ".$_GET['errorimg'].")</div>";
            }
        ?>
        <form action="treatmentAddImg.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group my-2">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Ajouter">
            </div>
        </form>
    </div>
    

    <?php
        include("partials/footer.php");
    ?>
</body>
</html>