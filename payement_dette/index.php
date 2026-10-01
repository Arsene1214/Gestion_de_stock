<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect(
    $serveur,
    $utilisateur,
    $motdepasse,
    $base
);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");


/* =====================================================
   NOMBRE TOTAL DE PAIEMENTS
===================================================== */

$sql = "
    SELECT COUNT(*) AS total
    FROM payement_dette
";

$resultat = mysqli_query($conn, $sql);

$total_paiements = 0;

if ($resultat) {

    $data = mysqli_fetch_assoc($resultat);

    $total_paiements =
        $data['total'];
}


/* =====================================================
   TOTAL PAYE USD
===================================================== */

$sql = "
    SELECT SUM(montant_peye) AS total
    FROM payement_dette
    WHERE devise = 'USD'
";

$resultat = mysqli_query($conn, $sql);

$total_usd = 0;

if ($resultat) {

    $data = mysqli_fetch_assoc($resultat);

    $total_usd =
        $data['total'];
}


/* =====================================================
   TOTAL PAYE CDF
===================================================== */

$sql = "
    SELECT SUM(montant_peye) AS total
    FROM payement_dette
    WHERE devise = 'CDF'
";

$resultat = mysqli_query($conn, $sql);

$total_cdf = 0;

if ($resultat) {

    $data = mysqli_fetch_assoc($resultat);

    $total_cdf =
        $data['total'];
}


/* =====================================================
   RESTE USD
===================================================== */

$sql = "
    SELECT SUM(reste) AS total
    FROM payement_dette
    WHERE devise = 'USD'
";

$resultat = mysqli_query($conn, $sql);

$reste_usd = 0;

if ($resultat) {

    $data = mysqli_fetch_assoc($resultat);

    $reste_usd =
        $data['total'];
}


/* =====================================================
   RESTE CDF
===================================================== */

$sql = "
    SELECT SUM(reste) AS total
    FROM payement_dette
    WHERE devise = 'CDF'
";

$resultat = mysqli_query($conn, $sql);

$reste_cdf = 0;

if ($resultat) {

    $data = mysqli_fetch_assoc($resultat);

    $reste_cdf =
        $data['total'];
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Paiement des dettes
</title>


<style>

* {
    box-sizing: border-box;
}


body {

    margin: 0;

    font-family: Arial, sans-serif;

    background: #f4f6f8;
}


/* =====================================================
   MENU
===================================================== */

.navbar {

    background: #343a40;

    padding: 15px 30px;

    display: flex;

    justify-content: space-between;

    align-items: center;
}


.navbar h2 {

    color: white;

    margin: 0;
}


.menu {

    display: flex;

    gap: 10px;
}


.menu a {

    color: white;

    text-decoration: none;

    padding: 10px 15px;

    border-radius: 5px;
}


.menu a:hover {

    background: #495057;
}


/* =====================================================
   CONTENU
===================================================== */

.container {

    width: 90%;

    max-width: 1200px;

    margin: 30px auto;
}


.titre {

    text-align: center;

    margin-bottom: 30px;
}


.titre h1 {

    color: #333;
}


.titre p {

    color: #666;
}


/* =====================================================
   CARTES
===================================================== */

.cards {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 20px;

    margin-bottom: 30px;
}


.card {

    background: white;

    padding: 25px;

    border-radius: 10px;

    text-align: center;

    box-shadow:
        0 2px 8px
        rgba(0,0,0,0.08);
}


.card h3 {

    color: #555;

    font-size: 16px;

    margin-top: 0;
}


.card .nombre {

    font-size: 25px;

    font-weight: bold;

    color: #28a745;
}


/* =====================================================
   CARTES RESTE
===================================================== */

.card.reste .nombre {

    color: #dc3545;
}


/* =====================================================
   ACTIONS
===================================================== */

.actions {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 20px;
}


.action {

    background: white;

    padding: 25px;

    border-radius: 10px;

    text-align: center;

    box-shadow:
        0 2px 8px
        rgba(0,0,0,0.08);
}


.action h3 {

    color: #333;

    margin-top: 0;
}


.action p {

    color: #666;

    line-height: 1.5;
}


/* =====================================================
   BOUTONS
===================================================== */

.btn {

    display: inline-block;

    padding: 11px 18px;

    color: white;

    text-decoration: none;

    border-radius: 5px;

    margin-top: 10px;
}


.btn-ajouter {

    background: #28a745;
}


.btn-ajouter:hover {

    background: #218838;
}


.btn-liste {

    background: #007bff;
}


.btn-liste:hover {

    background: #0056b3;
}


.btn-bordereau {

    background: #17a2b8;
}


.btn-bordereau:hover {

    background: #138496;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 900px) {

    .cards {

        grid-template-columns:
            repeat(2, 1fr);
    }

    .actions {

        grid-template-columns:
            1fr;
    }
}


@media (max-width: 600px) {

    body {

        padding: 0;
    }


    .navbar {

        flex-direction: column;

        gap: 15px;

        text-align: center;
    }


    .menu {

        flex-direction: column;

        width: 100%;
    }


    .menu a {

        text-align: center;
    }


    .container {

        width: 95%;
    }


    .cards {

        grid-template-columns: 1fr;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     MENU
===================================================== -->

<div class="navbar">

    <h2>
        Paiement des dettes
    </h2>


    <div class="menu">

        <a href="index.php">
            Accueil
        </a>

        <a href="ajouter.php">
            Ajouter
        </a>

        <a href="liste.php">
            Liste
        </a>

    </div>

</div>


<!-- =====================================================
     CONTENU
===================================================== -->

<div class="container">


<div class="titre">

    <h1>
        Gestion des paiements de dettes
    </h1>

    <p>
        Tableau de bord
    </p>

</div>


<!-- =====================================================
     STATISTIQUES
===================================================== -->

<div class="cards">


    <!-- NOMBRE DE PAIEMENTS -->

    <div class="card">

        <h3>
            Nombre de paiements
        </h3>

        <div class="nombre">

            <?php

            echo number_format(
                $total_paiements,
                0,
                ',',
                ' '
            );

            ?>

        </div>

    </div>


    <!-- TOTAL USD -->

    <div class="card">

        <h3>
            Total payé USD
        </h3>

        <div class="nombre">

            <?php

            echo number_format(
                $total_usd,
                2,
                ',',
                ' '
            );

            ?>

            USD

        </div>

    </div>


    <!-- TOTAL CDF -->

    <div class="card">

        <h3>
            Total payé CDF
        </h3>

        <div class="nombre">

            <?php

            echo number_format(
                $total_cdf,
                2,
                ',',
                ' '
            );

            ?>

            CDF

        </div>

    </div>


    <!-- RESTE USD -->

    <div class="card reste">

        <h3>
            Reste USD
        </h3>

        <div class="nombre">

            <?php

            echo number_format(
                $reste_usd,
                2,
                ',',
                ' '
            );

            ?>

            USD

        </div>

    </div>


</div>


<!-- =====================================================
     DEUXIEME LIGNE STATISTIQUE
===================================================== -->

<div class="cards">


    <div class="card reste">

        <h3>
            Reste CDF
        </h3>

        <div class="nombre">

            <?php

            echo number_format(
                $reste_cdf,
                2,
                ',',
                ' '
            );

            ?>

            CDF

        </div>

    </div>


</div>


<!-- =====================================================
     ACTIONS
===================================================== -->

<div class="actions">


    <!-- AJOUTER -->

    <div class="action">

        <h3>
            Ajouter un paiement
        </h3>

        <p>
            Enregistrer un nouveau
            paiement de dette.
        </p>

        <a
            href="ajouter.php"
            class="btn btn-ajouter"
        >

            + Ajouter

        </a>

    </div>


    <!-- LISTE -->

    <div class="action">

        <h3>
            Liste des paiements
        </h3>

        <p>
            Consulter, modifier ou
            supprimer les paiements.
        </p>

        <a
            href="liste.php"
            class="btn btn-liste"
        >

            Voir la liste

        </a>

    </div>


    <!-- BORDEREAU -->

    <div class="action">

        <h3>
            Bordereau
        </h3>

        <p>
            Pour imprimer un bordereau,
            sélectionnez d'abord un
            paiement dans la liste.
        </p>

        <a
            href="liste.php"
            class="btn btn-bordereau"
        >

            Imprimer

        </a>

    </div>


</div>


</div>


</body>

</html>
