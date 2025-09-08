<?php
    session_start();
    require "../connexion.php";

    //echo password_hash("epse",PASSWORD_ARGON2I);

    // si formulaire envoyé

    //var_dump($_POST);

    //var_dump($_COOKIE);

    // vérifier si SESSION
    if(isset($_SESSION['id']))
    {
        header("LOCATION:dashboard.php");
        exit();
    }

    // vérifier s'il y a un cookie
    if(isset($_COOKIE['remember_me']) && isset($_COOKIE['myid']))
    {
        if(is_numeric($_COOKIE['myid']))
        {
            // vérifier si l'id est un utilisateur
            $access = $bdd->prepare("SELECT id, login, password, connexion FROM members WHERE id=?");
            $access->execute([$_COOKIE['myid']]);
            $donAccess=$access->fetch(PDO::FETCH_ASSOC);
            $access->closeCursor();
            if($donAccess)
            {
                // ok pour l'id
                // vérifier token
                if(password_verify($_COOKIE['remember_me'],$donAccess['connexion']))
                {
                    //création des sessions
                    $_SESSION['id'] = $donAccess['id'];
                    $_SESSION['login'] = $donAccess['login'];
                    header("LOCATION:dashboard.php");
                    exit();
                }else{
                    // ⚠️ Token invalide → suppression des cookies
                setcookie("remember_me", "", time() - 3600, "/", "", false, true);
                setcookie("myid", "", time() - 3600, "/", "", false, true);
                $error = "Votre session a expiré, veuillez vous reconnecter.";
                }
            }else{
                // pas ok pour l'id
                header("LOCATION:index.php?id");
                exit();
            }  
        }else{
            header("LOCATION:index.php?numeric");
            exit();
        }
    }
    
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

                    // gestion de "rester connecté"
                    if(isset($_POST['remember']))
                    {
                        $token = bin2hex(random_bytes(32));
                        $hashtoken = password_hash($token,PASSWORD_DEFAULT);
                        setcookie('myid',$don['id'], time()+365*24*3600,"","",false,true);
                        setcookie('remember_me',$token, time() + 365*24*3600, "", "", false, true);
                        $update = $bdd->prepare("UPDATE members SET connexion=:connex WHERE id=:myid");
                        $update->execute([
                            ":connex" => $hashtoken,
                            ":myid" => $don['id'],
                        ]);
                    }
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
                    <div class="form-group my-2">
                        <!-- contrairement aux autres input, les checkbox doivent être cochées pour transmettre des informations -->
                        <input type="checkbox" name="remember" id="remember" value="ok">
                        <label for="remember">Rester connecté</label>
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