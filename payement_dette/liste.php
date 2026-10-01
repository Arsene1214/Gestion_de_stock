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
   RECHERCHE
===================================================== */

$recherche = "";

if (isset($_GET['recherche'])) {
    $recherche = trim($_GET['recherche']);
}


/* =====================================================
   REQUETE DES PAIEMENTS
===================================================== */

$sql = "
    SELECT
        payement_dette.*,

        dette.nature_dete,
        dette.montant_dette AS montant_dette

    FROM payement_dette

    LEFT JOIN dette
        ON payement_dette.id_dette = dette.id_dette
";


/* =====================================================
   FILTRE DE RECHERCHE
===================================================== */

if ($recherche != "") {

    $recherche_securisee =
        mysqli_real_escape_string(
            $conn,
            $recherche
        );

    $sql .= "
        WHERE
            payement_dette.date LIKE
            '%$recherche_securisee%'

            OR payement_dette.devise LIKE
            '%$recherche_securisee%'

            OR dette.nature_dete LIKE
            '%$recherche_securisee%'
    ";
}


$sql .= "
    ORDER BY payement_dette.id_dette_pey DESC
";


$resultat = mysqli_query(
    $conn,
    $sql
);


if (!$resultat) {

    die(
        "Erreur de chargement : "
        . mysqli_error($conn)
    );
}


/* =====================================================
   TOTAUX
===================================================== */

$total_paye_usd = 0;
$total_paye_cdf = 0;

$total_reste_usd = 0;
$total_reste_cdf = 0;

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Liste des paiements de dettes
</title>


<style>

* {
    box-sizing: border-box;
}


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

    box-shadow:
        0 0 10px
        rgba(0,0,0,0.1);
}


h2 {

    text-align: center;

    color: #28a745;

    margin-bottom: 25px;
}


/* =====================================================
   BOUTON AJOUTER
===================================================== */

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


/* =====================================================
   RECHERCHE
===================================================== */

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


/* =====================================================
   TABLEAU
===================================================== */

.table-container {

    overflow-x: auto;
}


table {

    width: 100%;

    border-collapse: collapse;

    min-width: 950px;
}


th {

    background: #28a745;

    color: white;

    padding: 12px;

    border: 1px solid #ddd;

    text-align: center;
}


td {

    padding: 10px;

    border: 1px solid #ddd;

    text-align: center;
}


tr:nth-child(even) {

    background: #f9f9f9;
}


/* =====================================================
   BOUTONS
===================================================== */

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


/* =====================================================
   TOTAUX
===================================================== */

.totaux {

    margin-top: 25px;

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


.total-usd {

    border-left: 5px solid #28a745;
}


.total-cdf {

    border-left: 5px solid #007bff;
}

</style>

</head>


<body>


<div class="container">


<h2>
    Liste des paiements de dettes
</h2>


<!-- =====================================================
     AJOUTER
===================================================== -->

<a
    href="ajouter.php"
    class="btn-ajouter"
>

    + Ajouter un paiement

</a>


<!-- =====================================================
     RECHERCHE
===================================================== -->

<form
    method="GET"
    action="liste.php"
    class="recherche"
>

    <input
        type="text"
        name="recherche"
        placeholder="Rechercher une dette..."
        value="<?php
            echo htmlspecialchars(
                $recherche
            );
        ?>"
    >

    <button type="submit">
        Rechercher
    </button>

</form>


<!-- =====================================================
     TABLEAU
===================================================== -->

<div class="table-container">

<table>

<thead>

<tr>

    <th>
        N°
    </th>

    <th>
        Date
    </th>

    <th>
        Dette
    </th>

    <th>
        Montant dette
    </th>

    <th>
        Montant payé
    </th>

    <th>
        Reste
    </th>

    <th>
        Devise
    </th>

    <th>
        Actions
    </th>

</tr>

</thead>


<tbody>


<?php

$numero = 1;


if (mysqli_num_rows($resultat) > 0) {


    while (
        $paiement =
        mysqli_fetch_assoc($resultat)
    ) {


        $montant_dette =
            (float) $paiement['montant_dette'];


        $montant_paye =
            (float) $paiement['montant_peye'];


        /*
         * On recalcule le reste
         */

        $reste =
            $montant_dette
            - $montant_paye;


        if ($reste < 0) {

            $reste = 0;
        }


        /* ==============================
           TOTAUX PAR DEVISE
        ============================== */

        if (
            $paiement['devise']
            == "USD"
        ) {

            $total_paye_usd +=
                $montant_paye;

            $total_reste_usd +=
                $reste;

        }


        if (
            $paiement['devise']
            == "CDF"
        ) {

            $total_paye_cdf +=
                $montant_paye;

            $total_reste_cdf +=
                $reste;
        }

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
            strtotime(
                $paiement['date']
            )
        );

        ?>

    </td>


    <!-- DETTE -->

    <td>

        <?php

        if (
            !empty(
                $paiement['nature_dete']
            )
        ) {

            echo htmlspecialchars(
                $paiement['nature_dete']
            );

        } else {

            echo "Dette inconnue";
        }

        ?>

    </td>


    <!-- MONTANT DETTE -->

    <td>

        <?php

        echo number_format(
            $montant_dette,
            2,
            ',',
            ' '
        );

        ?>

    </td>


    <!-- MONTANT PAYE -->

    <td>

        <strong>

        <?php

        echo number_format(
            $montant_paye,
            2,
            ',',
            ' '
        );

        ?>

        </strong>

    </td>


    <!-- RESTE -->

    <td>

        <strong>

        <?php

        echo number_format(
            $reste,
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
            $paiement['devise']
        );

        ?>

    </td>


    <!-- ACTIONS -->

    <td>


        <a
            href="modifier.php?id=<?php
                echo $paiement['id_dette_pey'];
            ?>"
            class="btn-modifier"
        >
        Modifier

</a>

       <a
            href="Bordereau.php?id=<?php
                echo $paiement['id_dette_pey'];
            ?>"
            class="btn-Bordereau"
        >
        Imprimer

</a>


<a
    href="supprimer.php?id=<?php
        echo $paiement['id_dette_pey'];
    ?>"
    class="btn-supprimer"

    onclick="
        return confirm(
            'Voulez-vous vraiment supprimer ce paiement ?'
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

<td colspan="8">

Aucun paiement trouvé.

</td>

</tr>


<?php

}

?>


</tbody>

</table>

</div>


<!-- =====================================================
TOTAUX
===================================================== -->

<div class="totaux">


<!-- TOTAL PAYE USD -->

<div class="total-box total-usd">

Total payé USD :

<?php

echo number_format(
    $total_paye_usd,
    2,
    ',',
    ' '
);

?>

USD

</div>


<!-- RESTE USD -->

<div class="total-box total-usd">

Reste USD :

<?php

echo number_format(
    $total_reste_usd,
    2,
    ',',
    ' '
);

?>

USD

</div>


<!-- TOTAL PAYE CDF -->

<div class="total-box total-cdf">

Total payé CDF :

<?php

echo number_format(
    $total_paye_cdf,
    2,
    ',',
    ' '
);

?>

CDF

</div>


<!-- RESTE CDF -->

<div class="total-box total-cdf">

Reste CDF :

<?php

echo number_format(
    $total_reste_cdf,
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

