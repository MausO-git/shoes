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
        <h1>Gestion des marques</h1>
        <a href="addMarque.php" class="btn btn-success my-3">Ajouter une marque</a>
        <?php
            if(isset($_GET['add'])){
                if($_GET['add'] == "success"){
                    echo "<div class='alert alert-success'>Vous avez bien ajouté une nouvelle marque à la base de données</div>";
                }
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
                    require "../connexion.php";
                    $req = $bdd->query("SELECT * FROM marque ORDER BY id DESC");
                    while($don = $req->fetch(PDO::FETCH_ASSOC)){
                        echo "<tr>";
                            echo "<td>".$don['id']."</td>";
                            echo "<td>".$don['nom']."</td>";
                            echo "<td>";
                                echo "<a href='updateMarque.php?id=".$don['id']."' class='btn btn-warning'>Modifier</a>";
                                echo "<a href='#' class='btn btn-danger mx-2'>Supprimer</a>";
                            echo "</td>";
                        echo "</tr>";
                    }

                ?>
            </tbody>
        </table>
    </div>

    <?php
        include("partials/footer.php");
    ?>
</body>
</html>