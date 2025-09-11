<h2 class="m-4 text-primary">Les derniers modèles</h2>
<div class="container-fluid text-center">
    <div class="row justify-content-center">
    <?php
        $req = $bdd->query("SELECT p.id AS pid, p.nom AS nom, p.cover AS cover, p.prix AS prix, m.nom AS marque FROM products p INNER JOIN marque m ON p.marque = m.id ORDER BY p.id DESC LIMIT 3");
        while($don = $req->fetch(PDO::FETCH_ASSOC)){
            echo "<div class='col-3 text-center m-1'>";
                echo "<div class='card border'>";
                    echo "<img src='images/".$don['cover']."' alt='image de ".$don['nom']."' class='img-top mb-1'>";
                    echo "<div class='row-fluid my-1'>".$don['nom']."</div>";
                    echo "<div class='row-fluid my-1'>".$don['marque']."</div>";
                    echo "<div class='row-fluid my-1'>".$don['prix']." €</div>";
                    echo "<a href='modele-".$don['pid']."' class='btn btn-primary mx-5 mt-1 mb-3'>Afficher</a>";
                echo "</div>";
            echo "</div>";
        }
    ?>
    </div>
</div>
<a href="all" class='btn btn-success m-4'>Voir tout les modèles</a>