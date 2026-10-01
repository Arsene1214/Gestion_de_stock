
<?php
include("header.php");
include("sidebar.php");
?>

<div class="main-content">

    <div class="container-fluid">

        <h2 class="mb-4">
            <i class="fas fa-folder-open"></i>
            Pages de l'application
        </h2>

        <div class="row">

            <div class="col-md-3 mb-4">
                <a href="accueil.php" class="btn btn-primary w-100 p-4">
                    <i class="fas fa-home fa-2x"></i><br><br>
                    Accueil
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="aide.php" class="btn btn-info w-100 p-4 text-white">
                    <i class="fas fa-question-circle fa-2x"></i><br><br>
                    Aide
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="apropos.php" class="btn btn-warning w-100 p-4">
                    <i class="fas fa-info-circle fa-2x"></i><br><br>
                    À propos
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="rapports.php" class="btn btn-secondary w-100 p-4">
                    <i class="fas fa-chart-bar fa-2x"></i><br><br>
                    Rapports
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="profil.php" class="btn btn-dark w-100 p-4">
                    <i class="fas fa-user fa-2x"></i><br><br>
                    Mon Profil
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="parametres.php" class="btn btn-danger w-100 p-4">
                    <i class="fas fa-cog fa-2x"></i><br><br>
                    Paramètres
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="logout.php" class="btn btn-outline-danger w-100 p-4">
                    <i class="fas fa-sign-out-alt fa-2x"></i><br><br>
                    Déconnexion
                </a>
            </div>

        </div>

    </div>

</div>

<?php
include("footer.php");
?>