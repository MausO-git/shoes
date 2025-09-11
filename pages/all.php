<h2 class="m-4 text-primary">Tout les modèles</h2>
<div class="container-fluid text-center">
    <div class="row justify-content-center">
    <?php
        $req = $bdd->prepare("SELECT p.id AS pid, p.nom AS nom, p.cover AS cover, p.prix AS prix, m.nom AS marque FROM products p INNER JOIN marque m ON p.marque = m.id ORDER BY p.id DESC LIMIT :offset,:limit");
        $req->bindValue(":offset", $offset, PDO::PARAM_INT);
        $req->bindValue(":limit", $limit, PDO::PARAM_INT);
        $req->execute();
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
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
                if($pg>1){
                    echo "<li class='page-item'><a href='all-page-".($pg - 1)."' class='page-link'>Previous</a></li>";
                }
                for($cpt=1; $cpt <= $nbPage; $cpt++){
                    echo "<li class='page-item'><a href='all-page-".$cpt."' class='page-link'>".$cpt."</a></li>";
                }
                if($pg!=$nbPage){
                    echo "<li class='page-item'><a href='all-page-".($pg + 1)."' class='page-link'>Next</a></li>";
                }
            ?>
        </ul>
    </nav>
</div>