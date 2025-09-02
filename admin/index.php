<?php
    session_start();
    require "../connexion.php";

    //echo password_hash("epse",PASSWORD_ARGON2I);

    // si formulaire envoyé
    if(isset($_POST['login']) && isset($_POST['password']))
    {
        // vérif le formulaire
        if(empty($_POST['login']) || empty($_POST['password']))
        {
            $error = "Veuillez remplir correctement le formulaire";
        }else{
            // tester les informations avec l'aide de la bdd
            // req
            $login = htmlspecialchars($_POST['login']);
            $req = $bdd->prepare("SELECT id, login, password FROM members WHERE login=?");
            $req->execute([$login]);
            $don = $req->fetch(PDO::FETCH_ASSOC);
            $req->closeCursor();
            if($don)
            {
                // test du mot de passe
                if(password_verify($_POST['password'],$don['password']))
                {
                    $_SESSION['id']=$don['id'];
                    $_SESSION['login']=$don['login'];
                    header("LOCATION:dashboard.php");
                    exit();
                }else{
                    $error = "Votre login ou votre mot de passe n'est pas correct";
                }
            }else{
                $error = "Votre login ou votre mot de passe n'est pas correct";
            }



        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Administration</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h1>Connexion</h1>
                <form action="index.php" method="POST">
                    <?php
                        if(isset($error))
                        {
                            echo '<div class="alert alert-danger">'.$error.'</div>';
                        }
                    ?>
                   
                    <div class="form-group my-2">
                        <label for="login">Login</label>
                        <input type="text" name="login" id="login" class="form-control">
                    </div>
                    <div class="form-group my-2">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Connexion" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>