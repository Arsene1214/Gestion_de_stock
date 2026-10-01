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
   REQUÊTE
   ============================== */

$sql = "SELECT 
            stock.*,
            devise.nom_devise,
            approvisionnement.produit
        FROM stock

        LEFT JOIN devise
            ON stock.id_devise = devise.id_devise

        LEFT JOIN approvisionnement
            ON stock.id_appro = approvisionnement.id_appro";


if ($recherche != "") {

    $recherche_securisee = mysqli_real_escape_string($conn, $recherche);

    $sql .= " WHERE
                stock.nom_produit LIKE '%$recherche_securisee%'
                OR devise.nom_devise LIKE '%$recherche_securisee%'
                OR approvisionnement.produit LIKE '%$recherche_securisee%'
                OR stock.date LIKE '%$recherche_securisee%'";
}


$sql .= " ORDER BY stock.id_stock DESC";


$resultat = mysqli_query($conn, $sql);

if (!$resultat) {
    die("Erreur de chargement : " . mysqli_error($conn));
}


/* ==============================
   TOTAL
   ============================== */

$total_quantite = 0;
$total_valeur = 0;

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Liste des stocks</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 25px;
}

.container {
    width: 95%;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #333;
}


/* RECHERCHE */

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


/* BOUTON AJOUTER */

.btn-ajouter {
    display: inline-block;
    padding: 10px 15px;
    background: green;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 15px;
}

.btn-ajouter:hover {
    background: #006400;
}


/* TABLEAU */

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th {
    background: #333;
    color: white;
    padding: 12px;
}

td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

tr:nth-child(even) {
    background: #f9f9f9;
}


/* BOUTONS */

.btn-modifier {
    background: orange;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
}

.btn-supprimer {
    background: red;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
}


/* TOTAL */

.total {
    margin-top: 20px;
    padding: 15px;
    background: #eee;
    border-radius: 5px;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="container">

<h2>Liste des stocks</h2>


<!-- BOUTON AJOUTER -->

<a href="ajouter.php" class="btn-ajouter">
    + Ajouter un stock
</a>


<!-- RECHERCHE -->

<form method="GET" action="liste.php" class="recherche">

    <input
        type="text"
        name="recherche"
        placeholder="Rechercher un produit, une devise..."
        value="<?php echo htmlspecialchars($recherche); ?>"
    >

    <button type="submit">
        Rechercher
    </button>

</form>


<!-- TABLEAU -->

<div class="table-container">

<table>

<thead>

<tr>

    <th>N°</th>
    <th>Date</th>
    <th>Produit</th>
    <th>Quantité</th>
    <th>Prix unitaire</th>
    <th>Montant total</th>
    <th>Approvisionnement</th>
    <th>Devise</th>
    <th>Actions</th>

</tr>

</thead>


<tbody>

<?php

$numero = 1;

if (mysqli_num_rows($resultat) > 0) {

    while ($stock = mysqli_fetch_assoc($resultat)) {

        $quantite = (float)$stock['quantite'];
        $prix_unitaire = (float)$stock['prix_unitaire'];

        $montant_total = $quantite * $prix_unitaire;

        $total_quantite += $quantite;
        $total_valeur += $montant_total;

?>

<tr>

    <td>
        <?php echo $numero++; ?>
    </td>


    <td>
        <?php
        echo htmlspecialchars(
            date("d/m/Y", strtotime($stock['date']))
        );
        ?>
    </td>


    <td>
        <?php
        echo htmlspecialchars($stock['nom_produit']);
        ?>
    </td>


    <td>
        <?php
        echo number_format($quantite, 0, ',', ' ');
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
    </td>


    <td>
        <?php
        echo number_format(
            $montant_total,
            2,
            ',',
            ' '
        );
        ?>
    </td>


    <td>
        <?php

        if (!empty($stock['produit'])) {

            echo htmlspecialchars($stock['produit']);

        } else {

            echo "Non défini";

        }

        ?>
    </td>


    <td>
        <?php

        if (!empty($stock['nom_devise'])) {

            echo htmlspecialchars($stock['nom_devise']);

        } else {

            echo "Non défini";

        }

        ?>
    </td>


    <td>

        <a
            href="modifier.php?id=<?php echo $stock['id_stock']; ?>"
            class="btn-modifier"
        >
            Modifier
        </a>

        <a
            href="supprimer.php?id=<?php echo $stock['id_stock']; ?>"
            class="btn-supprimer"
            onclick="return confirm('Voulez-vous vraiment supprimer ce stock ?');"
        >
            Supprimer
        </a>

    </td>

</tr>

<?php

    }

} else {

?>

<tr>

    <td colspan="9">
        Aucun stock trouvé.
    </td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>


<!-- TOTAUX -->

<div class="total">

    Quantité totale :
    <?php
    echo number_format(
        $total_quantite,
        0,
        ',',
        ' '
    );
    ?>

    &nbsp;&nbsp; | &nbsp;&nbsp;

    Valeur totale :
    <?php
    echo number_format(
        $total_valeur,
        2,
        ',',
        ' '
    );
    ?>

</div>

</div>

</body>

</html>
