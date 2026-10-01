<?php  
include("header.php");  
include("sidebar.php");  
include("connexion.php");  

// Comptage des enregistrements
$nbEmployes = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM employe")); 
$nbClients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM client"));
$nbFournisseurs = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM fournisseur"));
$nbArticles = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM article"));
$nbStock = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM stock"));
$nbAppros = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM approvisionnement"));
$nbVentes = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM vente"));
$nbFactures = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM facture"));
$nbDepenses = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM depenses"));
$nbCaisses = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM caisse"));
$nbDettes = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM dette"));
$nbPeyements = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM payement_dette"));
$nbDevises = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM devise"));
?>

<!-- CONTENU PRINCIPAL -->
<div class="main-content">

    <!-- En-tête de la page -->
    <div class="header-title">
        <div>
            <h3>Gestion d'Entreprise | 2025-2026</h3>
            <small>Système de Gestion d'Entreprise</small>
        </div>
        <div class="date-bar">
            <i class="far fa-calendar-alt me-2"></i>
            <?php setlocale(LC_TIME, 'fr_FR.utf8', 'fra'); echo strftime('%A, %d %B %Y | %H:%M:%S'); ?>
        </div>
    </div>

    <!-- SECTION 1 : TABLEAU DE BORD -->
    <div class="section-title section-blue">
        <i class="fas fa-th-large"></i> GESTION COMMERCIALE ET RESSOURCES HUMAINES
    </div>

    <!-- GRILLE DES CARTES -->
    <div class="dashboard-grid">
        
        <!-- Employés -->
        <div class="stat-card">
            <div class="card-top-line line-blue"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-blue"><i class="fas fa-users"></i></div>
                <div class="card-text-info">
                    <h5>Employés</h5>
                    <h3><?= $nbEmployes ?></h3>
                </div>
            </div>
            <a href="employe/index.php" class="btn-custom-bottom bg-blue"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Clients -->
        <div class="stat-card">
            <div class="card-top-line line-green"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-green"><i class="fas fa-user-friends"></i></div>
                <div class="card-text-info">
                    <h5>Clients</h5>
                    <h3><?= $nbClients ?></h3>
                </div>
            </div>
            <a href="client/index.php" class="btn-custom-bottom bg-green"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Fournisseurs -->
        <div class="stat-card">
            <div class="card-top-line line-purple"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-purple"><i class="fas fa-truck"></i></div>
                <div class="card-text-info">
                    <h5>Fournisseurs</h5>
                    <h3><?= $nbFournisseurs ?></h3>
                </div>
            </div>
            <a href="fournisseur/index.php" class="btn-custom-bottom bg-purple"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Approvisionnements -->
        <div class="stat-card">
            <div class="card-top-line line-orange"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-orange"><i class="fas fa-clipboard-list"></i></div>
                <div class="card-text-info">
                    <h5>Approvisionnements</h5>
                    <h3><?= $nbAppros ?></h3>
                </div>
            </div>
            <a href="approvisionnement/index.php" class="btn-custom-bottom bg-orange"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Stock -->
        <div class="stat-card">
            <div class="card-top-line line-red"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-red"><i class="fas fa-box"></i></div>
                <div class="card-text-info">
                    <h5>Stock</h5>
                    <h3><?= $nbStock ?></h3>
                </div>
            </div>
            <a href="stock/index.php" class="btn-custom-bottom bg-red"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Articles -->
        <div class="stat-card">
            <div class="card-top-line line-teal"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-teal"><i class="fas fa-cubes"></i></div>
                <div class="card-text-info">
                    <h5>Articles</h5>
                    <h3><?= $nbArticles ?></h3>
                </div>
            </div>
            <a href="article/index.php" class="btn-custom-bottom bg-teal"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Factures -->
        <div class="stat-card">
            <div class="card-top-line line-purple"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-purple"><i class="fas fa-file-invoice"></i></div>
                <div class="card-text-info">
                    <h5>Factures</h5>
                    <h3><?= $nbFactures ?></h3>
                </div>
            </div>
            <a href="facture/index.php" class="btn-custom-bottom bg-purple"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Ventes -->
        <div class="stat-card">
            <div class="card-top-line line-pink"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-pink"><i class="fas fa-shopping-cart"></i></div>
                <div class="card-text-info">
                    <h5>Ventes</h5>
                    <h3><?= $nbVentes ?></h3>
                </div>
            </div>
            <a href="vente/index.php" class="btn-custom-bottom bg-pink"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>
        
    </div> <!-- Fin de la grille -->

    <!-- SECTION 2 : GESTION FINANCIÈRE -->
    <div class="section-title section-purple">
        <i class="fas fa-user-tie"></i> GESTION FINANCIÈRE
    </div>

    <!-- GRILLE DES CARTES FINANCIÈRES -->
    <div class="dashboard-grid">

        <!-- Dettes -->
        <div class="stat-card">
            <div class="card-top-line line-orange"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-orange"><i class="fas fa-download"></i></div>
                <div class="card-text-info">
                    <h5>Dettes</h5>
                    <h3><?= $nbDettes ?></h3>
                </div>
            </div>
            <a href="dette/index.php" class="btn-custom-bottom bg-orange"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Caisse -->
        <div class="stat-card">
            <div class="card-top-line line-green"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-green"><i class="fas fa-money-bill-wave"></i></div>
                <div class="card-text-info">
                    <h5>Caisse</h5>
                    <h3><?= $montantCaisse ?></h3>
                </div>
            </div>
            <a href="caisse/index.php" class="btn-custom-bottom bg-green"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Dépenses -->
        <div class="stat-card">
            <div class="card-top-line line-red"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-red"><i class="fas fa-chart-line"></i></div>
                <div class="card-text-info">
                    <h5>Dépenses</h5>
                    <h3><?= $montantDepenses ?></h3>
                </div>
            </div>
            <a href="depences/index.php" class="btn-custom-bottom bg-red"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Devises -->
        <div class="stat-card">
            <div class="card-top-line line-teal"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-teal"><i class="fas fa-exchange-alt"></i></div>
                <div class="card-text-info">
                    <h5>Devises</h5>
                    <h3><?= $nbDevises ?></h3>
                </div>
            </div>
            <a href="devise/index.php" class="btn-custom-bottom bg-teal"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

        <!-- Paiements -->
        <div class="stat-card">
            <div class="card-top-line line-indigo"></div>
            <div class="card-body-custom">
                <div class="icon-box bg-indigo"><i class="fas fa-credit-card"></i></div>
                <div class="card-text-info">
                    <h5>Paiements</h5>
                    <h3><?= $nbPeyements ?></h3>
                </div>
            </div>
            <a href="payement_dette/index.php" class="btn-custom-bottom bg-indigo"><i class="fas fa-play me-1"></i> OUVRIR</a>
        </div>

    </div> <!-- Fin de la grille -->

</div>

<?php  
include("footer.php");  
?>
