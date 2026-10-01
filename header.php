<?php 
session_start();  
 
if(!isset($_SESSION['user'])){  
    header("Location: index.php");  
    exit();  
}  
?>  
<!DOCTYPE html>  <html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <title>GESTION D'ENTREPRISE</title>  
    <!-- Bootstrap -->  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">  
    <!-- Font Awesome -->  
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">  
     <!-- CSS -->  
      <link rel="stylesheet" href="style.css"> 
     </head>  
     <body>  
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">  
            <div class="container-fluid">  
                <button class="btn btn-light me-3" id="menu-btn">  
                    <i class="fas fa-bars"></i>
                </button>  
<a class="navbar-brand fw-bold" href="dashboard.php">  
<i class="fas fa-boxes-stacked"></i>  
GESTION D'ENTREPRISE  
</a>  <div class="ms-auto d-flex align-items-center">  
    <div class="text-white me-4">  
        <i class="fas fa-calendar-alt"></i>
<span id="date">
</span>
</div>  
<div class="text-white me-4">  
    <i class="fas fa-clock"></i>
<span id="clock"></span>

</div>  <div class="dropdown">  
    <a class="btn btn-light dropdown-toggle"  
data-bs-toggle="dropdown">

<i class="fas fa-user-circle"></i>

<?php echo $_SESSION['user']; ?>  
</a>  
<ul class="dropdown-menu dropdown-menu-end">  
    <li>  
<a class="dropdown-item" href="#">  
<i class="fas fa-user"></i>  
Mon profil  
</a>  
</li>  
<link rel="stylesheet" href="style.css">

<li>  
<hr class="dropdown-divider">  
</li>  
<li>  
<a class="dropdown-item text-danger" href="deconnexion.php">  
<i class="fas fa-sign-out-alt"></i>  
Déconnexion  
</a>  
</li>  
</ul>  
</div>  
</div>  
</div>  
</nav>  
<div class="container-fluid">  
<div class="row">
