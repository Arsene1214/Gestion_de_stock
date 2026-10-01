<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

/* ==============================
   STATISTIQUES DU STOCK
   ============================== */

$sql_total = "SELECT COUNT(*) AS total FROM stock";
$resultat_total = mysqli_query($conn, $sql_total);
$total_stock = mysqli_fetch_assoc($resultat_total);

$sql_quantite = "SELECT SUM(quantite) AS total_quantite FROM stock";
$resultat_quantite = mysqli_query($conn, $sql_quantite);
$total_quantite = mysqli_fetch_assoc($resultat_quantite);

$sql_valeur = "SELECT SUM(quantite * prix_unitaire) AS total_valeur
               FROM stock";
$resultat_valeur = mysqli_query($conn, $sql_valeur);
$total_valeur = mysqli_fetch_assoc($resultat_valeur);

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Gestion du stock</title>

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
    background: #222;
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
    background: #444;
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
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.card h3 {
    margin-top: 0;
    color: #555;
}

.card .nombre {
    font-size: 30px;
    font-weight: bold;
    color: #007bff;
}


/* ==============================
   ACTIONS
   ============================== */

.actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.action {
    background: white;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.action h3 {
    margin-top: 0;
}

.btn {
    display: inline-block;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
}

.btn-ajouter {
    background: green;
}

.btn-ajouter:hover {
    background: #006400;
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

@media (max-width: 700px) {

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

    <h2>Gestion de Stock</h2>

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

    <h1>Gestion du stock</h1>

    <p>
        Bienvenue dans le système de gestion des stocks.
    </p>

</div>


<!-- ==============================
     STATISTIQUES
     ============================== -->

<div class="cards">


    <div class="card">

        <h3>Nombre de produits</h3>

        <div class="nombre">
            <?php
            echo $total_stock;
            ?>
        </div>

    </div>


    <div class="card">

        <h3>Quantité totale</h3>

        <div class="nombre">

            <?php

            echo number_format((float)
                $total_quantite, 2, ',',''
            );

            ?>

        </div>

    </div>


    <div class="card">

        <h3>Valeur du stock</h3>

        <div class="nombre">

            <?php

            echo number_format((float)
                $total_valeur, 0, ',', ' '
            );

            ?>

        </div>

    </div>

</div>


<!-- ==============================
     ACTIONS
     ============================== -->

<div class="actions">


    <div class="action">

        <h3>Ajouter un stock</h3>

        <p>
            Enregistrer un nouveau produit
            dans le stock.
        </p>

        <a
            href="ajouter.php"
            class="btn btn-ajouter"
        >
            + Ajouter un stock
        </a>

    </div>


    <div class="action">

        <h3>Consulter le stock</h3>

        <p>
            Voir, rechercher, modifier
            ou supprimer les stocks.
        </p>

        <a
            href="liste.php"
            class="btn btn-liste"
        >
            Voir la liste
        </a>

    </div>


</div>

</div>

</body>

</html>

