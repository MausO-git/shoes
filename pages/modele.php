
<div class="container-lg">
    <h1><?= $donMod['nom'] ?></h1>
    <h2><?= $donMod['nMarque'] ?></h2>
    <div class="col-6 cover">
        <img src="images/<?= $donMod['cover'] ?>" alt="couverture de <?= $donMod['nom'] ?>">
    </div>
    <p><?= $donMod['descr'] ?></p>
    <h2 class="text-warning bg-dark rounded p-1 text-center" style="width: 10rem;"><?= $donMod['prix'] ?> €</h2>
</div>
<?php
    $statement = "SELECT * FROM images WHERE id_product=?";
    $rowCountGal = $bdd->prepare($statement);
    $rowCountGal->execute([$id]);
    $myCount = $rowCountGal->rowCount();
    if($myCount>0){
?>
<div class="container d-flex justify-content-center">
    <div id="carouselExample" class="carousel carousel-dark slide col-6">
        <div class="carousel-indicators">
            <?php
                for($cpt=0; $cpt<$myCount; $cpt++)
                    {
                        if($cpt==0)
                        {
                            echo ' <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$cpt.'" class="active" aria-current="true" aria-label="Slide '.($cpt+1).'"></button>';
                        }else{
                            echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$cpt.'" aria-label="Slide '.($cpt+1).'"></button>';
                        }
                    }
            ?>
        </div>
        <div class="carousel-inner">
            <?php
                $compt = 0;
                $imgReq = $bdd->prepare($statement);
                $imgReq->execute([$id]);
                $donImg = $imgReq->fetchAll(PDO::FETCH_ASSOC);
                foreach($donImg AS $image){
                    if($compt === 0){
                        echo "<div class='carousel-item active'>";
                            echo "<img src='images/".$image['fichier']."' class='d-block w-100 image-fluid' alt='".$donMod['nom']."'>";
                        echo "</div>";
                    }else{
                        echo "<div class='carousel-item'>";
                            echo "<img src='images/".$image['fichier']."' class='d-block w-100 image-fluid' alt='".$donMod['nom']."'>";
                        echo "</div>";
                    }
                    $compt++;
                }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<?php
    }else{
        echo "<p>Il n'y a pas d'image</p>";
    }
?>