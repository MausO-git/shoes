<div class="container">
    <h1>Recherche:</h1>
    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="search">
        <div class="form-group my-3">
            <label for="search">Recherche </label>
            <input type="text" id="search" name="search" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" value="Rechercher" class="btn btn-primary">
        </div>
    </form>
    <?php
        if(isset($search)){
            $searchReq = $bdd->prepare("SELECT products.cover AS cover, products.id AS pid, products.nom AS pnom, marque.nom AS mnom FROM products INNER JOIN marque ON products.marque = marque.id WHERE products.nom LIKE :rech OR marque.nom LIKE :rech");
            $searchReq->execute([
                ":rech" => "%".$search."%"
            ]);
            $countSearch = $searchReq->rowCount();
            $searchDon = $searchReq->fetchAll(PDO::FETCH_ASSOC);
            $searchReq->closeCursor();
            if($countSearch>0){
                foreach($searchDon as $sDon):
                
            ?>
                <div class="card col-md-3 p-2">
                    <div class="img">
                        <img src="images/<?= $sDon['cover'] ?>" alt="image de <?= $sDon['pnom'] ?>" class="img-fluid">
                    </div>
                    <div class="content">
                        <h4><?= $sDon['mnom'] ?></h4>
                        <h3><?= $sDon['pnom'] ?></h3>
                    </div>
                    <a href="product-<?= $sDon['pid'] ?>" class="btn btn-success">En savoir plus</a>
                </div>
            <?php
            endforeach;
            }else{
                echo "<div class='text-center'>Aucun résultat pour la recherche</div>";
            }
        }
    ?>
</div>