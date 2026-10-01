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
   RECHERCHE
   ============================== */

$recherche = "";

if (isset($_GET['recherche'])) {
    $recherche = trim($_GET['recherche']);
}


/* ==============================
   REQUETE
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
            ON vente.id_employe = employe.id_employe";


/* ==============================
   FILTRE DE RECHERCHE
   ============================== */

if ($recherche != "") {

    $recherche_securisee =
        mysqli_real_escape_string($conn, $recherche);

    $sql .= " WHERE
                vente.produit LIKE '%$recherche_securisee%'
                OR vente.date LIKE '%$recherche_securisee%'
                OR vente.devise LIKE '%$recherche_securisee%'
                OR article.nom_article LIKE '%$recherche_securisee%'
                OR employe.nom LIKE '%$recherche_securisee%'
                OR employe.postnom LIKE '%$recherche_securisee%'
                OR employe.prenom LIKE '%$recherche_securisee%'";
}


$sql .= " ORDER BY vente.id_vente DESC";


$resultat = mysqli_query($conn, $sql);

if (!$resultat) {
    die("Erreur de chargement : " . mysqli_error($conn));
}


/* ==============================
   TOTAUX
   ============================== */

$total_quantite = 0;
$total_montant_usd = 0;
$total_montant_cdf = 0;

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Liste des ventes</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 25px;
}

.container {
    width: 98%;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #28a745;
    margin-bottom: 25px;
}


/* ==============================
   BOUTON AJOUTER
   ============================== */

.btn-ajouter {
    display: inline-block;
    background: #28a745;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 15px;
}

.btn-ajouter:hover {
    background: #218838;
}


/* ==============================
   RECHERCHE
   ============================== */

.recherche {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.recherche input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.recherche button {
    padding: 10px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.recherche button:hover {
    background: #0056b3;
}


/* ==============================
   TABLEAU
   ============================== */

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1100px;
}

th {
    background: #28a745;
    color: white;
    padding: 12px;
    border: 1px solid #ddd;
}

td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

tr:nth-child(even) {
    background: #f9f9f9;
}


/* ==============================
   BOUTONS ACTIONS
   ============================== */

.btn-modifier {
    background: orange;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
    margin: 2px;
}

.btn-supprimer {
    background: red;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
    margin: 2px;
}


/* ==============================
   TOTAUX
   ============================== */

.totaux {
    margin-top: 20px;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.total-box {
    background: #f1f1f1;
    padding: 15px;
    border-radius: 5px;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="container">

<h2>Liste des ventes</h2>


<!-- ==============================
     AJOUTER
     ============================== -->

<a
    href="ajouter.php"
    class="btn-ajouter"
>
    + Ajouter une vente
</a>


<!-- ==============================
     RECHERCHE
     ============================== -->

<form
    method="GET"
    action="liste.php"
    class="recherche"
>

    <input
        type="text"
        name="recherche"
        placeholder="Rechercher une vente..."
        value="<?php echo htmlspecialchars($recherche); ?>"
    >

    <button type="submit">
        Rechercher
    </button>

</form>


<!-- ==============================
     TABLEAU
     ============================== -->

<div class="table-container">

<table>

<thead>

<tr>

    <th>N°</th>
    <th>Date</th>
    <th>Produit</th>
    <th>Article</th>
    <th>Quantité</th>
    <th>Prix unitaire</th>
    <th>Montant</th>
    <th>Devise</th>
    <th>Employé</th>
    <th>Actions</th>

</tr>

</thead>


<tbody>

<?php

$numero = 1;

if (mysqli_num_rows($resultat) > 0) {

    while ($vente = mysqli_fetch_assoc($resultat)) {

        $quantite =
            (float)$vente['quantite'];

        $PU =
            (float)$vente['PU'];

        /*
         * Recalcul automatique du montant
         */
        $montant =
            $quantite * $PU;


        $total_quantite += $quantite;


        if ($vente['devise'] == "USD") {

            $total_montant_usd += $montant;

        } elseif ($vente['devise'] == "CDF") {

            $total_montant_cdf += $montant;
        }


        $nom_employe =
            trim(
                $vente['nom'] . " "
                . $vente['postnom'] . " "
                . $vente['prenom']
            );

?>

<tr>

    <!-- NUMERO -->

    <td>
        <?php
        echo $numero++;
        ?>
    </td>


    <!-- DATE -->

    <td>

        <?php

        echo date(
            "d/m/Y",
            strtotime($vente['date'])
        );

        ?>

    </td>


    <!-- PRODUIT -->

    <td>

        <?php

        echo htmlspecialchars(
            $vente['produit']
        );

        ?>

    </td>


    <!-- ARTICLE -->

    <td>

        <?php

        if (!empty($vente['nom_article'])) {

            echo htmlspecialchars(
                $vente['nom_article']
            );

        } else {

            echo "Non défini";
        }

        ?>

    </td>


    <!-- QUANTITE -->

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


    <!-- PRIX UNITAIRE -->

    <td>

        <?php

        echo number_format(
            $PU,
            2,
            ',',
            ' '
        );

        ?>

    </td>


    <!-- MONTANT -->

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

        </strong>

    </td>


    <!-- DEVISE -->

    <td>

        <?php

        echo htmlspecialchars(
            $vente['devise']
        );

        ?>

    </td>


    <!-- EMPLOYE -->

    <td>

        <?php

        if ($nom_employe != "") {

            echo htmlspecialchars(
                $nom_employe
            );

        } else {

            echo "Non défini";
        }

        ?>

    </td>


    <!-- ACTIONS -->

    <td>

        <a
            href="modifier.php?id=<?php echo $vente['id_vente']; ?>"
            class="btn-modifier"
        >
            Modifier
        </a>


        <a
            href="suprimer.php?id=<?php echo $vente['id_vente']; ?>"
            class="btn-supprimer"
            onclick="return confirm('Voulez-vous vraiment supprimer cette vente ?');"
        >
            Supprimer
        </a>

        <a
            href="facture.php?id=<?php echo $vente['id_vente']; ?>"
            class="btn-modifier"
        >
            Modifier
        </a>

    </td>

</tr>

<?php

    }

} else {

?>

<tr>
<td colspan="10">

Aucune vente trouvée.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>


<!-- ==============================
TOTAUX
============================== -->

<div class="totaux">


<div class="total-box">

Quantité totale :

<?php

echo number_format(
    $total_quantite,
    0,
    ',',
    ' '
);

?>

</div>


<div class="total-box">

Total USD :

<?php

echo number_format(
    $total_montant_usd,
    2,
    ',',
    ' '
);

?>
USD

</div>


<div class="total-box">

Total CDF :

<?php

echo number_format(
    $total_montant_cdf,
    2,
    ',',
    ' '
);

?>
CDF

</div>


</div>

</div>

</body>

</html>

