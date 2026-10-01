<?php
// ==========================================================
//                 LISTE DES OPÉRATIONS
// ==========================================================

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

// Connexion
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


// ==========================================================
//                    RECHERCHE
// ==========================================================

$recherche = "";

if (isset($_GET['recherche'])) {

    $recherche = trim($_GET['recherche']);

    $recherche_sql = mysqli_real_escape_string(
        $conn,
        $recherche
    );

    $sql = "
        SELECT
            caisse.*,
            employe.nom,
            employe.postnom,
            employe.prenom

        FROM caisse

        LEFT JOIN employe
            ON caisse.id_employe = employe.id_employe

        WHERE
            caisse.reference_operation LIKE '%$recherche_sql%'
            OR caisse.categorie LIKE '%$recherche_sql%'
            OR caisse.type_operation LIKE '%$recherche_sql%'
            OR caisse.devise LIKE '%$recherche_sql%'
            OR caisse.mode_paiement LIKE '%$recherche_sql%'
            OR caisse.description LIKE '%$recherche_sql%'
            OR employe.nom LIKE '%$recherche_sql%'
            OR employe.postnom LIKE '%$recherche_sql%'
            OR employe.prenom LIKE '%$recherche_sql%'

        ORDER BY caisse.id DESC
    ";

} else {

    $sql = "
        SELECT
            caisse.*,
            employe.nom,
            employe.postnom,
            employe.prenom

        FROM caisse

        LEFT JOIN employe
            ON caisse.id_employe = employe.id_employe

        ORDER BY caisse.id DESC
    ";
}


// Exécution
$resultat = mysqli_query($conn, $sql);

if (!$resultat) {
    die(
        "Erreur SQL : " .
        mysqli_error($conn)
    );
}


// ==========================================================
//                       TOTAUX
// ==========================================================

$entree_usd = 0;
$sortie_usd = 0;

$entree_cdf = 0;
$sortie_cdf = 0;


// Récupération de toutes les opérations
$total = mysqli_query(
    $conn,
    "SELECT type_operation, devise, montant FROM caisse"
);

if ($total) {

    while ($t = mysqli_fetch_assoc($total)) {

        $montant = floatval($t['montant']);


        // ==============================
        // ENTRÉE
        // ==============================

        if ($t['type_operation'] == "ENTREE") {

            if ($t['devise'] == "USD") {

                $entree_usd += $montant;

            } else {

                $entree_cdf += $montant;
            }


        // ==============================
        // SORTIE
        // ==============================

        } elseif ($t['type_operation'] == "SORTIE") {

            if ($t['devise'] == "USD") {

                $sortie_usd += $montant;

            } else {

                $sortie_cdf += $montant;
            }
        }
    }
}


// ==========================================================
//                       SOLDES
// ==========================================================

$solde_usd =
    $entree_usd - $sortie_usd;

$solde_cdf =
    $entree_cdf - $sortie_cdf;

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Liste des opérations</title>


<style>

/* ==========================================================
                       STYLE GÉNÉRAL
========================================================== */

* {
    box-sizing: border-box;
}

body {

    font-family: Arial, sans-serif;

    background: #f4f6f8;

    margin: 20px;

    color: #333;
}


/* ==========================================================
                         TITRE
========================================================== */

h2 {

    text-align: center;

    color: #0066cc;

    margin-bottom: 25px;
}


/* ==========================================================
                         CARTES
========================================================== */

.cards {

    display: flex;

    flex-wrap: wrap;

    gap: 15px;

    margin-bottom: 25px;
}

.card {

    flex: 1;

    min-width: 200px;

    background: white;

    padding: 18px;

    text-align: center;

    border-radius: 10px;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.10);
}

.card h3 {

    margin: 0;

    color: #0066cc;

    font-size: 16px;
}

.card p {

    font-size: 20px;

    font-weight: bold;

    margin: 12px 0 0;
}


/* ==========================================================
                      RECHERCHE
========================================================== */

.recherche {

    background: white;

    padding: 15px;

    border-radius: 8px;

    margin-bottom: 20px;

    display: flex;

    gap: 10px;

    flex-wrap: wrap;
}

.recherche input {

    flex: 1;

    min-width: 250px;

    padding: 10px;

    border: 1px solid #ccc;

    border-radius: 5px;

    font-size: 14px;
}

.recherche button {

    padding: 10px 15px;

    border: none;

    border-radius: 5px;

    cursor: pointer;

    background: #0066cc;

    color: white;

    font-weight: bold;
}

.recherche button:hover {

    background: #004999;
}


/* ==========================================================
                     NOUVELLE OPÉRATION
========================================================== */

.btn-ajouter {

    padding: 10px 15px;

    background: green;

    color: white;

    text-decoration: none;

    border-radius: 5px;

    font-weight: bold;
}

.btn-ajouter:hover {

    background: #006600;
}


/* ==========================================================
                         TABLE
========================================================== */

.table-container {

    width: 100%;

    overflow-x: auto;

    background: white;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.10);
}

table {

    width: 100%;

    min-width: 1400px;

    border-collapse: collapse;
}

th {

    background: #0066cc;

    color: white;

    padding: 11px;

    border: 1px solid #ddd;

    white-space: nowrap;
}

td {

    padding: 9px;

    border: 1px solid #ddd;

    vertical-align: middle;
}

tr:nth-child(even) {

    background: #f9f9f9;
}

tr:hover {

    background: #eef6ff;
}


/* ==========================================================
                        STATUT
========================================================== */

.statut {

    font-weight: bold;

    text-align: center;

    padding: 5px 8px;

    border-radius: 5px;
}

.valide {

    background: #d4edda;

    color: #155724;
}

.attente {

    background: #fff3cd;

    color: #856404;
}


/* ==========================================================
                         ACTIONS
========================================================== */

.actions {

    white-space: nowrap;
}

.actions a {

    display: inline-block;

    padding: 6px 9px;

    margin: 2px;

    color: white;

    text-decoration: none;

    border-radius: 4px;

    font-size: 13px;
}

.modifier {

    background: green;
}

.supprimer {

    background: red;
}

.bordereau {

    background: orange;
}

.actions a:hover {

    opacity: 0.8;
}


/* ==========================================================
                     MESSAGE AUCUNE DONNÉE
========================================================== */

.aucune {

    text-align: center;

    padding: 25px;

    color: #777;

    font-weight: bold;
}

</style>

</head>


<body>


<h2>
    Liste des opérations de caisse
</h2>


<!-- ======================================================
                         CARTES
======================================================= -->

<div class="cards">

    <div class="card">

        <h3>Entrées USD</h3>

        <p>
            <?php echo number_format(
                $entree_usd,
                2,
                '.',
                ','
            ); ?>
            USD
        </p>

    </div>


    <div class="card">

        <h3>Sorties USD</h3>

        <p>
            <?php echo number_format(
                $sortie_usd,
                2,
                '.',
                ','
            ); ?>
            USD
        </p>

    </div>


    <div class="card">

        <h3>Solde USD</h3>

        <p>
            <?php echo number_format(
                $solde_usd,
                2,
                '.',
                ','
            ); ?>
            USD
        </p>

    </div>


    <div class="card">

        <h3>Solde CDF</h3>

        <p>
            <?php echo number_format(
                $solde_cdf,
                2,
                '.',
                ','
            ); ?>
            CDF
        </p>

    </div>

</div>


<!-- ======================================================
                         RECHERCHE
======================================================= -->

<form
    method="GET"
    class="recherche"
>

    <input
        type="text"
        name="recherche"
        placeholder="Rechercher une opération, catégorie, employé..."
        value="<?= htmlspecialchars($recherche); ?>"
    >


    <button type="submit">
        🔍 Rechercher
    </button>


    <a
        href="ajouter.php"
        class="btn-ajouter"
    >
        + Nouvelle opération
    </a>

</form>


<!-- ======================================================
                         TABLE
======================================================= -->

<div class="table-container">

<table>

<thead>

<tr>

    <th>ID</th>

    <th>Date</th>

    <th>Type</th>

    <th>Référence</th>

    <th>Catégorie</th>

    <th>Montant</th>

    <th>Devise</th>

    <th>Taux de change</th>

    <th>Montant CDF</th>

    <th>Mode de paiement</th>

    <th>Description</th>

    <th>Employé</th>

    <th>Statut</th>

    <th>Actions</th>

</tr>

</thead>


<tbody>


<?php

if (mysqli_num_rows($resultat) > 0) {

    while ($data = mysqli_fetch_assoc($resultat)) {

        // Nom complet de l'employé

        $employe = trim(
            ($data['nom']. '') . ' ' .
            ($data['postnom'].  '') . ' ' .
            ($data['prenom']. '')
        );

        if ($employe == '') {

            $employe = "Non attribué";
        }

?>

<tr>


<!-- ID -->

<td>

<?php echo htmlspecialchars($data['id']); ?>

</td>


<!-- DATE -->

<td>

<?php echo htmlspecialchars(
    $data['date']
); ?>

</td>


<!-- TYPE -->

<td>

<?php echo htmlspecialchars(
    $data['type_operation']
); ?>

</td>


<!-- REFERENCE -->

<td>

<?php echo htmlspecialchars(
    $data['reference_operation']
); ?>

</td>


<!-- CATEGORIE -->

<td>

<?php echo htmlspecialchars(
    $data['categorie']
); ?>

</td>


<!-- MONTANT -->

<td>

<?php echo number_format(
    $data['montant'],
    2,
    '.',
    ','
); ?>

</td>


<!-- DEVISE -->

<td>

<?php echo htmlspecialchars(
    $data['devise']
); ?>

</td>


<!-- TAUX -->

<td>

<?php echo number_format(
    $data['taux_change'],
    2,
    '.',
    ','
); ?>

</td>


<!-- MONTANT CDF -->

<td>

<?php echo number_format(
    $data['montant_cdf'],
    2,
    '.',
    ','
); ?>

CDF

</td>


<!-- MODE PAIEMENT -->

<td>

<?php echo htmlspecialchars(
    $data['mode_paiement']
); ?>

</td>


<!-- DESCRIPTION -->

<td>

<?php echo htmlspecialchars(
    $data['description']
); ?>

</td>


<!-- EMPLOYE -->

<td>

<?php echo htmlspecialchars(
    $employe
); ?>

</td>


<!-- STATUT -->

<td>

<?php

if ($data['statut'] == "VALIDÉ") {

    echo '
        <span class="statut valide">
            VALIDÉ
        </span>
    ';

} else {

    echo '
        <span class="statut attente">
            EN ATTENTE
        </span>
    ';
}

?>

</td>


<!-- ACTIONS -->

<td class="actions">


<a
    href="modifier.php?id=<?php echo $data['id']; ?>"
    class="modifier"
>
    Modifier
</a>


<a
    href="suprimer.php?id=<?php echo $data['id']; ?>"
    class="supprimer"
    onclick="return confirm(
        'Voulez-vous vraiment supprimer cette opération ?'
    );"
>
    Supprimer
</a>


<a
    href="bordereau.php?id=<?php echo $data['id']; ?>"
    class="bordereau"
>
    Bordereau
</a>


</td>


</tr>

<?php

    }

} else {

?>

<tr>

<td
    colspan="14"
    class="aucune"
>

Aucune opération trouvée.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>


</body>

</html>
