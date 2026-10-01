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
   VERIFICATION DE L'ID
   ============================== */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Vente introuvable.");
}

$id = intval($_GET['id']);


/* ==============================
   RECUPERATION DE LA VENTE
   ============================== */

$sql = "SELECT
            vente.*,
            article.nom_article,
            employe.nom,
            employe.postnom,
            employe.prenom

        FROM vente

        LEFT JOIN article
            ON vente.id_article = article.id_article

        LEFT JOIN employe
            ON vente.id_employe = employe.id_employe

        WHERE vente.id_vente = ?";


$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erreur de préparation : " . mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


mysqli_stmt_execute($stmt);

$resultat = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($resultat) == 0) {
    die("Vente introuvable.");
}


$vente = mysqli_fetch_assoc($resultat);

mysqli_stmt_close($stmt);


/* ==============================
   CALCUL DU MONTANT
   ============================== */

$quantite = (float)$vente['quantite'];

$prix_unitaire = (float)$vente['PU'];

$montant = $quantite * $prix_unitaire;


/* ==============================
   INFORMATIONS
   ============================== */

$employe = trim(
    $vente['nom'] . " "
    . $vente['postnom'] . " "
    . $vente['prenom']
);

$article = !empty($vente['nom_article'])
    ? $vente['nom_article']
    : "Non défini";

$devise = $vente['devise'];

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Facture de vente
</title>


<style>

body {

    font-family: Arial, sans-serif;

    background: #f2f2f2;

    margin: 0;

    padding: 30px;
}


.facture {

    width: 800px;

    max-width: 100%;

    margin: auto;

    background: white;

    padding: 35px;

    box-shadow:
        0 0 10px
        rgba(0,0,0,0.15);
}


/* ==============================
   EN-TETE
   ============================== */

.entete {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    border-bottom: 2px solid #333;

    padding-bottom: 20px;

    margin-bottom: 25px;
}


.entreprise h2 {

    margin: 0;

    font-size: 25px;
}


.entreprise p {

    margin: 5px 0;

    color: #555;
}


.titre-facture {

    text-align: right;
}


.titre-facture h1 {

    margin: 0;

    font-size: 30px;

    color: #28a745;
}


.titre-facture p {

    margin: 5px 0;
}


/* ==============================
   INFORMATIONS
   ============================== */

.informations {

    display: flex;

    justify-content: space-between;

    margin-bottom: 25px;
}


.info-box {

    width: 48%;

    background: #f7f7f7;

    padding: 15px;

    border-radius: 5px;
}


.info-box strong {

    display: block;

    margin-bottom: 7px;
}


/* ==============================
   TABLEAU
   ============================== */

table {

    width: 100%;

    border-collapse: collapse;

    margin-top: 20px;
}


th {

    background: #333;

    color: white;

    padding: 12px;

    text-align: center;
}


td {

    padding: 12px;

    border: 1px solid #ddd;

    text-align: center;
}


/* ==============================
   TOTAL
   ============================== */

.total {

    width: 300px;

    margin-left: auto;

    margin-top: 20px;
}


.total div {

    display: flex;

    justify-content: space-between;

    padding: 8px;

    border-bottom: 1px solid #ddd;
}


.total .grand-total {

    font-size: 20px;

    font-weight: bold;

    background: #f1f1f1;
}


/* ==============================
   SIGNATURE
   ============================== */

.signature {

    display: flex;

    justify-content: space-between;

    margin-top: 60px;

    text-align: center;
}


.signature div {

    width: 40%;
}


.ligne {

    border-top: 1px solid #333;

    margin-top: 50px;

    padding-top: 8px;
}


/* ==============================
   BOUTONS
   ============================== */

.actions {

    text-align: center;

    margin-top: 30px;
}


.btn {

    display: inline-block;

    padding: 10px 18px;

    margin: 5px;

    color: white;

    text-decoration: none;

    border-radius: 5px;

    border: none;

    cursor: pointer;

    font-size: 15px;
}


.btn-imprimer {

    background: #007bff;
}


.btn-retour {

    background: #6c757d;
}


/* ==============================
   IMPRESSION
   ============================== */

@media print {

    body {

        background: white;

        padding: 0;
    }


    .facture {

        width: 100%;

        box-shadow: none;

        padding: 10px;
    }


    .actions {

        display: none;
    }

}

</style>

</head>


<body>


<div class="facture">


<!-- ==============================
     EN-TETE
     ============================== -->

<div class="entete">


    <div class="entreprise">

        <h2>
            CICI
        </h2>

        <p>
            Gestion des ventes
        </p>

        <p>
            Vente de produits
        </p>

    </div>


    <div class="titre-facture">

        <h1>
            FACTURE
        </h1>

        <p>
            N° :
            <?php
            echo str_pad(
                $vente['id_vente'],
                5,
                '0',
                STR_PAD_LEFT
            );
            ?>
        </p>

        <p>

            Date :

            <?php

            echo date(
                "d/m/Y",
                strtotime(
                    $vente['date']
                )
            );

            ?>

        </p>

    </div>


</div>


<!-- ==============================
     INFORMATIONS
     ============================== -->

<div class="informations">


    <div class="info-box">

        <strong>
            Article
        </strong>

        <?php

        echo htmlspecialchars(
            $article
        );

        ?>

    </div>


    <div class="info-box">

        <strong>
            Vendeur
        </strong>

        <?php

        echo htmlspecialchars(
            $employe
        );

        ?>

    </div>


</div>


<!-- ==============================
     TABLEAU DE LA VENTE
     ============================== -->

<table>

<thead>

<tr>

    <th>
        Produit
    </th>

    <th>
        Quantité
    </th>

    <th>
        Prix unitaire
    </th>

    <th>
        Montant
    </th>

</tr>

</thead>


<tbody>

<tr>

    <td>

        <?php

        echo htmlspecialchars(
            $vente['produit']
        );

        ?>

    </td>


    <td>

        <?php

        echo number_format(
            $quantite,
            0,
            ',',
            ' '
        );

        ?>

    </td>


    <td>

        <?php

        echo number_format(
            $prix_unitaire,
            2,
            ',',
            ' '
        );

        ?>

        <?php
        echo htmlspecialchars($devise);
        ?>

    </td>


    <td>

        <strong>

        <?php

        echo number_format(
            $montant,
            2,
            ',',
            ' '
        );

        ?>

        <?php
        echo htmlspecialchars($devise);
        ?>

        </strong>

    </td>

</tr>

</tbody>

</table>


<!-- ==============================
     TOTAL
     ============================== -->

<div class="total">

    <div>

        <span>
            Quantité :
        </span>

        <strong>

            <?php

            echo number_format(
                $quantite,
                0,
                ',',
                ' '
            );

            ?>

        </strong>

    </div>


    <div class="grand-total">

        <span>
            TOTAL :
        </span>

        <strong>

            <?php

            echo number_format(
                $montant,
                2,
                ',',
                ' '
            );

            ?>

            <?php
            echo htmlspecialchars($devise);
            ?>

        </strong>

    </div>

</div>


<!-- ==============================
     SIGNATURES
     ============================== -->

<div class="signature">


    <div>

        <div class="ligne">

            Signature du vendeur

        </div>

    </div>


    <div>

        <div class="ligne">

            Signature du client

        </div>

    </div>


</div>


<!-- ==============================
     BOUTONS
     ============================== -->

<div class="actions">

    <button
        onclick="window.print()"
        class="btn btn-imprimer"
    >

        🖨 Imprimer la facture

    </button>


    <a
        href="liste.php"
        class="btn btn-retour"
    >

        ← Retour

    </a>

</div>


</div>


</body>

</html>
