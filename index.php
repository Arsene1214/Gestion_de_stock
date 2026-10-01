<?php  
session_start();  
include("connexion.php"); 

if(isset($_POST['login']))  
{  
    $username = mysqli_real_escape_string($conn, $_POST['username']);  
    $password = md5($_POST['password']);  
  
    $sql = "SELECT * FROM utilisateurs  
            WHERE username='$username'  
            AND password='$password'";  
  
    $result = mysqli_query($conn,$sql);  
  
    if(mysqli_num_rows($result)>0)  
    {  
        $_SESSION['user']=$username;  
        header("Location: dashboard.php");  
        exit();  
    }  
    else  
    {  
        $erreur="Nom d'utilisateur ou mot de passe incorrect.";  
    }  
}  
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
<meta charset="UTF-8">  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<title>Connexion | GESTION D'ENTREPRISE</title>  
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">  
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">  

<!-- CSS Spécifique pour le Login (Pour qu'il soit bien centré et beau) -->
<style>
    body.login-body {
        /* Fond dégradé bleu nuit */
        background: linear-gradient(135deg, #0b2d6d 0%, #1f5edb 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border: none;
    }

    .login-card .card-body {
        padding: 40px;
    }

    .icon-box {
        background: #0b2d6d;
        color: white;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(11, 45, 109, 0.4);
    }

    .btn-login {
        background-color: #0b2d6d;
        border: none;
        padding: 12px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-login:hover {
        background-color: #1f5edb;
    }
</style>

</head>  
<body class="login-body">  

<div class="container">  
    <div class="row justify-content-center">  
        <div class="col-md-5">  
            <div class="card login-card shadow-lg border-0">  
                <div class="card-body">  
                    
                    <div class="text-center">  
                        <!-- Boîte d'icône stylée -->
                        <div class="icon-box">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>

                        <h2 class="fw-bold text-dark">GESTION D'ENTREPRISE</h2>  
                        <p class="text-muted">  
                            Connectez-vous pour continuer  
                        </p>  
                    </div>  

                    <?php  
                    if(isset($erreur))  
                    {  
                        echo "<div class='alert alert-danger text-center'>$erreur</div>";  
                    }  
                    ?>  

                    <form method="POST">  
                        <div class="mb-3">  
                            <label class="form-label fw-bold">Nom d'utilisateur</label>  
                            <input type="text" name="username" class="form-control" placeholder="Entrez votre nom" required>  
                        </div>  

                        <div class="mb-4">  
                            <label class="form-label fw-bold">Mot de passe</label>  
                            <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" required>  
                        </div>  

                        <button type="submit" name="login" class="btn btn-primary w-100 btn-login">  
                            <i class="fa fa-sign-in-alt me-2"></i> Se connecter  
                        </button>  
                    </form>  

                    <hr>  
                    <div class="text-center text-secondary">  
                        © 2025 GESTION D'ENTREPRISE  
                    </div>  
                </div>  
            </div>  
        </div>  
    </div>  
</div>  

<script src="script.js"></script>  
</body>  
</html>

