<?php
include("header.php");
include("sidebar.php");
?>

<div class="main-content">

    <div class="card shadow-lg border-0">
        <div class="card-body">

            <h1 class="text-primary">
                <i class="fas fa-home"></i>
                Accueil
            </h1>

            <hr>

            <h3>Bienvenue dans le système de gestion de stock</h3>

            <p>
                Cette application permet de gérer efficacement :
            </p>

            <ul>
                <li>Les employés</li>
                <li>Les clients</li>
                <li>Les fournisseurs</li>
                <li>Les articles</li>
                <li>Le stock</li>
                <li>Les approvisionnements</li>
                <li>Les ventes</li>
                <li>Les factures</li>
                <li>Les dépenses</li>
                <li>Les dettes</li>
                <li>La caisse</li>
                <li>Les devises</li>
                <li>Les Peyements</li>
            </ul>

        </div>
    </div>

    <div class="row mt-4">

    <li class="nav-item">  
            <a href="dashboard.php" class="nav-link text-white">
                <i class="fas fa-chart-line fa-2x"></i><br><br>
                Tableau de bord
                </a>  
            </li>  

        <li class="nav-item">  
        <a href="employe/index.php" class="nav-link text-white">  
            <i class="fas fa-users"></i>  
            Employés  
        </a>  
    </li>  

    <li class="nav-item">  
        <a href="client/index.php" class="nav-link text-white">  
            <i class="fas fa-user-friends"></i>  
            Clients  
        </a>  
    </li>  

    <li class="nav-item">  
        <a href="fournisseur/index.php" class="nav-link text-white">  
            <i class="fas fa-truck"></i>  
            Fournisseurs  
        </a>  
    </li>  
    <li class="nav-item">  
        <a href="approvisionnement/index.php" class="nav-link text-white">  
            <i class="fas fa-tags"></i>  
            Approvisionnement 
        </a>  
    </li>  

    <li class="nav-item">  
        <a href="article/index.php" class="nav-link text-white">  
            <i class="fas fa-box-open"></i>  
            Articles  
        </a>  
    </li>  

    <li class="nav-item">  
        <a href="stock/index.php" class="nav-link text-white">  
            <i class="fas fa-warehouse"></i>  
            Stock  
        </a>  
    </li> 

    <li class="nav-item">  
        <a href="vente/index.php" class="nav-link text-white">  
            <i class="fas fa-shopping-cart"></i>  
            Ventes  
        </a>  
    </li>  

    <li class="nav-item">  
        <a href="facture/index.php" class="nav-link text-white">  
            <i class="fas fa-file-invoice"></i>  
            Factures  
        </a>  
    </li>  

    <li class="nav-item">  
        <a href="caisse/index.php" class="nav-link text-white">  
            <i class="fas fa-cash-register"></i>  
            Caisse  
        </a>  
    </li> 
    
    

    <li class="nav-item">  
        <a href="devise/index.php" class="nav-link text-white">  
            <i class="fas fa-warehouse"></i>  
            Devise  
        </a>  
    </li> 

    <li class="nav-item">  
        <a href="payement_dette/index.php" class="nav-link text-white">  
            <i class="fas fa-shopping-cart"></i>  
            Payement  
        </a>  
    </li>  

    <li class="nav-item">  
        <a href="dette/index.php" class="nav-link text-white">  
            <i class="fas fa-file-invoice"></i>  
            dette  
        </a>  
    </li>  

    <li class="nav-item">  
        <a href="depenses/index.php" class="nav-link text-white">  
            <i class="fas fa-cash-register"></i>  
            Depenses  
        </a>  
    </li> 

</div>

<?php
include("footer.php");
?>
