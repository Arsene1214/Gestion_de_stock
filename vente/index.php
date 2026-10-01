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


/* ==============================
   NOMBRE TOTAL DE VENTES
   ============================== */

$sql_ventes = "
    SELECT COUNT(*) AS total
    FROM vente
";

$resultat_ventes = mysqli_query(
    $conn,
    $sql_ventes
);

$total_ventes = 0;

if ($resultat_ventes) {

    $donnees = mysqli_fetch_assoc(
        $resultat_ventes
    );

    $total_ventes = $donnees['total'];
}


/* ==============================
   QUANTITE TOTALE
   ============================== */

$sql_quantite = "
    SELECT SUM(quantite) AS total_quantite
    FROM vente
";

$resultat_quantite = mysqli_query(
    $conn,
    $sql_quantite
);

$total_quantite = 0;

if ($resultat_quantite) {

    $donnees = mysqli_fetch_assoc(
        $resultat_quantite
    );

    $total_quantite =
        $donnees['total_quantite'];
}


/* ==============================
   TOTAL USD
   ============================== */

$sql_usd = "
    SELECT SUM(montant) AS total_usd
    FROM vente
    WHERE devise = 'USD'
";

$resultat_usd = mysqli_query(
    $conn,
    $sql_usd
);

$total_usd = 0;

if ($resultat_usd) {

    $donnees = mysqli_fetch_assoc(
        $resultat_usd
    );

    $total_usd =
        $donnees['total_usd'];
}


/* ==============================
   TOTAL CDF
   ============================== */

$sql_cdf = "
    SELECT SUM(montant) AS total_cdf
    FROM vente
    WHERE devise = 'CDF'
";

$resultat_cdf = mysqli_query(
    $conn,
    $sql_cdf
);

$total_cdf = 0;

if ($resultat_cdf) {

    $donnees = mysqli_fetch_assoc(
        $resultat_cdf
    );

    $total_cdf =
        $donnees['total_cdf'];
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Gestion des ventes</title>


<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    font-family: Arial, sans-serif;

    background: #f4f6f8;
}


/* ==============================
   MENU
   ============================== */

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


/* ==============================
   CONTENU
   ============================== */

.container {

    width: 90%;

    max-width: 1100px;

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


/* ==============================
   CARTES
   ============================== */

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

    margin-top: 0;

    color: #555;

    font-size: 17px;
}

.card .nombre {

    font-size: 27px;

    font-weight: bold;

    color: #28a745;
}


/* ==============================
   ACTIONS
   ============================== */

.actions {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

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

    margin-top: 0;

    color: #333;
}

.action p {

    color: #666;
}


/* ==============================
   BOUTONS
   ============================== */

.btn {

    display: inline-block;

    padding: 12px 20px;

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


/* ==============================
   RESPONSIVE
   ============================== */

@media (max-width: 850px) {

    .cards {

        grid-template-columns:
            repeat(2, 1fr);
    }
}

@media (max-width: 600px) {

    .navbar {

        flex-direction: column;

        gap: 15px;
    }

    .menu {

        flex-direction: column;

        width: 100%;
    }

    .menu a {

        text-align: center;
    }

    .cards {

        grid-template-columns: 1fr;
    }

    .actions {

        grid-template-columns: 1fr;
    }
}

</style>

</head>


<body>


<!-- ==============================
     MENU
     ============================== -->

<div class="navbar">

    <h2>
        Gestion des ventes
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


<!-- ==============================
     CONTENU
     ============================== -->

<div class="container">


<div class="titre">

    <h1>
        Gestion des ventes
    </h1>

    <p>
        Tableau de bord des ventes
    </p>

</div>


<!-- ==============================
     STATISTIQUES
     ============================== -->

<div class="cards">


    <!-- NOMBRE DE VENTES -->

    <div class="card">

        <h3>
            Nombre de ventes
        </h3>

        <div class="nombre">

            <?php

            echo number_format(
                $total_ventes,
                0,
                ',',
                ' '
            );

            ?>

        </div>

    </div>


    <!-- QUANTITE -->

    <div class="card">

        <h3>
            Quantité vendue
        </h3>

        <div class="nombre">

            <?php

            echo number_format(
                $total_quantite,
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
            Total USD
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
            Total CDF
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

</div>


<!-- ==============================
     ACTIONS
     ============================== -->

<div class="actions">


    <!-- AJOUTER UNE VENTE -->

    <div class="action">

        <h3>
            Ajouter une vente
        </h3>

        <p>
            Enregistrer une nouvelle
            vente dans le système.
        </p>

        <a
            href="ajouter.php"
            class="btn btn-ajouter"
        >

            + Ajouter une vente

        </a>

    </div>


    <!-- LISTE DES VENTES -->

    <div class="action">

        <h3>
            Consulter les ventes
        </h3>

        <p>
            Voir, rechercher, modifier
            ou supprimer les ventes.
        </p>

        <a
            href="liste.php"
            class="btn btn-liste"
        >

            Voir les ventes

        </a>

    </div>


</div>


</div>


</body>

</html>
