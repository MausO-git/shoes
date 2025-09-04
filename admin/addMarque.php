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
    <title>Document</title>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>

    <div class="container-fluid">
        <h1>Ajouter des marques</h1>
        <a href="marques.php" class="btn btn-success my-3">Retour</a>
        <?php
            if(isset($_GET['error'])){
                echo "<div class='alert alert-danger'>Une erreur est survenue (code erreur: ".$_GET['error'].")</div>";
            }
        ?>
        <form action="treatmentAddMarque.php" method="POST">
            <div class="form-group my-2">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" class="form-control">
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