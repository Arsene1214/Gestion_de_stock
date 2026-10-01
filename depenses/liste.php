
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


// ======================================================
// SUPPRESSION
// ======================================================

if (isset($_GET['supprimer'])) {

    $id = intval($_GET['supprimer']);

    $sql_delete = "DELETE FROM depenses WHERE id = $id";

    if (mysqli_query($conn, $sql_delete)) {

        header("Location: liste.php");
        exit();

    } else {

        $erreur = "Erreur lors de la suppression : "
                . mysqli_error($conn);
    }
}


// ======================================================
// RECHERCHE
// ======================================================

$recherche = "";

if (isset($_GET['recherche'])) {

    $recherche = mysqli_real_escape_string(
        $conn,
        $_GET['recherche']
    );
}


// ======================================================
// REQUETE PRINCIPALE
// ======================================================

$sql = "
    SELECT
        depenses.*,

        employe.nom,
        employe.postnom,
        employe.prenom,

        caisse.date AS date_caisse,
        caisse.type_operation,
        caisse.categorie AS categorie_caisse

    FROM depenses

    LEFT JOIN employe
        ON depenses.id_employe = employe.id_employe

    LEFT JOIN caisse
        ON depenses.id= caisse.id
";


// ======================================================
// FILTRE DE RECHERCHE
// ======================================================

if (!empty($recherche)) {

    $sql .= "
        WHERE
            depenses.categorie LIKE '%$recherche%'
            OR depenses.devise LIKE '%$recherche%'
            OR depenses.mode_paiement LIKE '%$recherche%'
            OR depenses.beneficiaire LIKE '%$recherche%'
            OR depenses.reference_paiement LIKE '%$recherche%'
            OR depenses.statut LIKE '%$recherche%'
            OR employe.nom LIKE '%$recherche%'
            OR employe.postnom LIKE '%$recherche%'
            OR employe.prenom LIKE '%$recherche%'
    ";
}


$sql .= "
    ORDER BY depenses.id DESC
";


$resultat = mysqli_query($conn, $sql);

if (!$resultat) {

    die(
        "Erreur lors du chargement des dépenses : "
        . mysqli_error($conn)
    );
}


// ======================================================
// CALCUL DES TOTAUX
// ======================================================

$total_usd = 0;
$total_cdf = 0;
$total_converti = 0;

$nombre_depenses = mysqli_num_rows($resultat);


// On remet le pointeur au début
mysqli_data_seek($resultat, 0);

while ($ligne_total = mysqli_fetch_assoc($resultat)) {

    if ($ligne_total['devise'] == "USD") {

        $total_usd += floatval(
            $ligne_total['montant']
        );

    } elseif ($ligne_total['devise'] == "CDF") {

        $total_cdf += floatval(
            $ligne_total['montant']
        );
    }

    $total_converti += floatval(
        $ligne_total['montant_converti']
    );
}


// On remet encore le pointeur au début
mysqli_data_seek($resultat, 0);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>Liste des dépenses</title>


<style>

* {
    box-sizing: border-box;
}

body {

    font-family: Arial, sans-serif;

    background: #f4f4f4;

    margin: 0;

    padding: 20px;
}


/* ==================================================
   CONTENEUR
================================================== */

.container {

    width: 100%;

    max-width: 1500px;

    margin: auto;

    background: white;

    padding: 20px;

    border-radius: 10px;

    box-shadow:
        0 2px 10px rgba(0,0,0,0.1);
}


h2 {

    text-align: center;

    color: #333;

    margin-bottom: 20px;
}


/* ==================================================
   BOUTONS
================================================== */

.actions {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 10px;

    margin-bottom: 20px;

    flex-wrap: wrap;
}


.btn {

    display: inline-block;

    padding: 10px 15px;

    border-radius: 5px;

    text-decoration: none;

    color: white;

    border: none;

    cursor: pointer;
}


.btn-ajouter {

    background: #28a745;
}


.btn-ajouter:hover {

    background: #218838;
}


.btn-modifier {

    background: #007bff;
}


.btn-modifier:hover {

    background: #0056b3;
}


.btn-supprimer {

    background: #dc3545;
}


.btn-supprimer:hover {

    background: #c82333;
}


/* ==================================================
   RECHERCHE
================================================== */

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

    font-size: 15px;
}


.recherche button {

    background: #343a40;

    color: white;

    border: none;

    padding: 10px 20px;

    border-radius: 5px;

    cursor: pointer;
}


.recherche button:hover {

    background: #23272b;
}


/* ==================================================
   STATISTIQUES
================================================== */

.statistiques {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 15px;

    margin-bottom: 20px;
}


.stat {

    background: #f8f9fa;

    border: 1px solid #ddd;

    padding: 15px;

    border-radius: 8px;

    text-align: center;
}


.stat h3 {

    margin: 0 0 8px 0;

    font-size: 16px;

    color: #555;
}


.stat p {

    margin: 0;

    font-size: 20px;

    font-weight: bold;
}


/* ==================================================
   TABLEAU
================================================== */

.table-container {

    overflow-x: auto;

    width: 100%;
}


table {

    width: 100%;

    border-collapse: collapse;

    min-width: 1500px;
}


th {

    background: #343a40;

    color: white;

    padding: 10px;

    border: 1px solid #ddd;

    white-space: nowrap;
}


td {

    padding: 9px;

    border: 1px solid #ddd;

    text-align: center;

    white-space: nowrap;
}


tr:nth-child(even) {

    background: #f8f9fa;
}


tr:hover {

    background: #e9ecef;
}


/* ==================================================
   STATUT
================================================== */

.statut-valide {

    color: #155724;

    background: #d4edda;

    padding: 5px 8px;

    border-radius: 4px;

    font-weight: bold;
}


.statut-annule {

    color: #721c24;

    background: #f8d7da;

    padding: 5px 8px;

    border-radius: 4px;

    font-weight: bold;
}


/* ==================================================
   VIDE
================================================== */

.vide {

    text-align: center;

    padding: 30px;

    color: #777;

    font-size: 17px;
}


/* ==================================================
   MOBILE
================================================== */

@media(max-width: 800px) {

    body {

        padding: 10px;
    }

    .container {

        padding: 10px;
    }

    .statistiques {

        grid-template-columns:
            repeat(2, 1fr);
    }

    .recherche {

        flex-direction: column;
    }

    .recherche button {

        width: 100%;
    }
}


@media(max-width: 500px) {

    .statistiques {

        grid-template-columns: 1fr;
    }
}

</style>

</head>


<body>


<div class="container">


<h2>Liste des dépenses</h2>


<!-- ==================================================
     MESSAGE D'ERREUR
================================================== -->

<?php

if (isset($erreur)) {

?>

<div
    style="
        background:#f8d7da;
        color:#721c24;
        padding:12px;
        margin-bottom:15px;
        border-radius:5px;
    "
>

    <?php
    echo htmlspecialchars($erreur);
    ?>

</div>

<?php

}

?>


<!-- ==================================================
     ACTIONS
================================================== -->

<div class="actions">

    <a
        href="ajouter.php"
        class="btn btn-ajouter"
    >
        + Ajouter une dépense
    </a>


    <a
        href="../index.php"
        class="btn btn-supprimer"
    >
        Retour
    </a>

</div>


<!-- ==================================================
     RECHERCHE
================================================== -->

<form
    method="GET"
    class="recherche"
>

    <input
        type="text"
        name="recherche"
        placeholder="Rechercher une dépense..."
        value="<?php
            echo htmlspecialchars($recherche);
        ?>"
    >

    <button type="submit">
        Rechercher
    </button>

</form>


<!-- ==================================================
     STATISTIQUES
================================================== -->

<div class="statistiques">


    <div class="stat">

        <h3>Nombre de dépenses</h3>

        <p>
            <?php
            echo $nombre_depenses;
            ?>
        </p>

    </div>


    <div class="stat">

        <h3>Total USD</h3>

        <p>
            <?php
            echo number_format(
                $total_usd,
                2,
                ',',
                ' '
            );
            ?>
            USD
        </p>

    </div>


    <div class="stat">

        <h3>Total CDF</h3>

        <p>
            <?php
            echo number_format(
                $total_cdf,
                2,
                ',',
                ' '
            );
            ?>
            CDF
        </p>

    </div>


    <div class="stat">

        <h3>Total converti</h3>

        <p>
            <?php
            echo number_format(
                $total_converti,
                2,
                ',',
                ' '
            );
            ?>
        </p>

    </div>

</div>


<!-- ==================================================
     TABLEAU
================================================== -->

<div class="table-container">

<table>

<thead>

<tr>

    <th>ID</th>

    <th>Date</th>

    <th>Catégorie</th>

    <th>Montant</th>

    <th>Devise</th>

    <th>Taux change</th>

    <th>Montant converti</th>

    <th>Mode paiement</th>

    <th>Bénéficiaire</th>

    <th>Référence</th>

    <th>Description</th>

    <th>Justificatif</th>

    <th>Statut</th>

    <th>Employé</th>

    <th>Caisse</th>

    <th>Actions</th>

</tr>

</thead>


<tbody>


<?php

if ($nombre_depenses > 0) {

    while ($depense =
        mysqli_fetch_assoc($resultat)) {

?>

<tr>


    <!-- ID -->

    <td>

        <?php
        echo $depense['id'];
        ?>

    </td>


    <!-- DATE -->

    <td>

        <?php
        echo htmlspecialchars(
            $depense['date']
        );
        ?>

    </td>


    <!-- CATEGORIE -->

    <td>

        <?php
        echo htmlspecialchars(
            $depense['categorie']
        );
        ?>

    </td>


    <!-- MONTANT -->

    <td>

        <?php
        echo number_format(
            $depense['montant'],
            2,
            ',',
            ' '
        );
        ?>

    </td>


    <!-- DEVISE -->

    <td>

        <?php
        echo htmlspecialchars(
            $depense['devise']
        );
        ?>

    </td>


    <!-- TAUX -->

    <td>

        <?php
        echo number_format(
            $depense['taux_change'],
            2,
            ',',
            ' '
        );
        ?>

    </td>


    <!-- MONTANT CONVERTI -->

    <td>

        <?php
        echo number_format(
            $depense['montant_converti'],
            2,
            ',',
            ' '
        );
        ?>

    </td>


    <!-- MODE PAIEMENT -->

    <td>

        <?php
        echo htmlspecialchars(
            $depense['mode_paiement']
        );
        ?>

    </td>


    <!-- BENEFICIAIRE -->

    <td>

        <?php
        echo htmlspecialchars(
            $depense['beneficiaire']
        );
        ?>

    </td>


    <!-- REFERENCE -->

    <td>

        <?php
        echo htmlspecialchars(
            $depense['reference_paiement']
        );
        ?>

    </td>


    <!-- DESCRIPTION -->

    <td>

        <?php
        echo htmlspecialchars(
            $depense['description']
        );
        ?>

    </td>


    <!-- JUSTIFICATIF -->

    <td>

        <?php
        echo htmlspecialchars(
            $depense['justificatif']
        );
        ?>

    </td>


    <!-- STATUT -->

    <td>

        <?php

        if ($depense['statut'] == 'VALIDE') {

        ?>

            <span class="statut-valide">
                VALIDE
            </span>

        <?php

        } else {

        ?>

            <span class="statut-annule">
                ANNULE
            </span>

        <?php

        }

        ?>

    </td>


    <!-- EMPLOYE -->

    <td>

        <?php

        if (!empty($depense['nom'])) {

            echo htmlspecialchars(

                $depense['nom'] . " " .
                $depense['postnom'] . " " .
                $depense['prenom']

            );

        } else {

            echo "Non renseigné";

        }

        ?>

    </td>


    <!-- CAISSE -->

    <td>

        <?php

        if (!empty($depense['date_caisse'])) {

            echo htmlspecialchars(

                $depense['date_caisse']
                . " - "
                . $depense['type_operation']
                . " - "
                . $depense['categorie_caisse']

            );

        } else {

            echo "Non renseignée";

        }

        ?>

    </td>


    <!-- ACTIONS -->

    <td>

        <a
            href="modifier.php?id=<?php
                echo $depense['id'];
            ?>"
            class="btn btn-modifier"
        >
            Modifier
        </a>


        <a
            href="liste.php?supprimer=<?php
                echo $depense['id'];
            ?>"
            class="btn btn-supprimer"
            onclick="
                return confirm(
                    'Voulez-vous vraiment supprimer cette dépense ?'
                );
            "
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

    <td
        colspan="16"
        class="vide"
    >

        Aucune dépense trouvée.

    </td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>


</div>

</body>

</html>
