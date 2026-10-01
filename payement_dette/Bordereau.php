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
   VERIFICATION DE L'ID DU PAIEMENT
===================================================== */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du paiement manquant.");
}

$id = (int) $_GET['id'];

/* =====================================================
   RECUPERATION DU PAIEMENT
===================================================== */

$sql = "
    SELECT
        payement_dette.*,
        dette.nature_dete,
        dette.montant_dette AS montant_dette

    FROM payement_dette

    LEFT JOIN dette
        ON payement_dette.id_dette = dette.id_dette

    WHERE payement_dette.id_dette_pey = ?
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die(
        "Erreur de préparation SQL : "
        . mysqli_error($conn)
    );
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$resultat = mysqli_stmt_get_result($stmt);

if (!$resultat) {
    die(
        "Erreur lors de la récupération : "
        . mysqli_error($conn)
    );
}

if (mysqli_num_rows($resultat) == 0) {
    die("Paiement introuvable pour l'ID : " . $id);
}

$paiement = mysqli_fetch_assoc($resultat);

mysqli_stmt_close($stmt);

/* =====================================================
   DONNEES DU PAIEMENT
===================================================== */

$montant_dette = (float) $paiement['montant_dette'];

$montant_paye = (float) $paiement['montant_peye'];

/* Calcul du reste */
$reste = $montant_dette - $montant_paye;

/*
   Si la colonne reste existe dans payement_dette,
   utiliser sa valeur.
*/
if (
    isset($paiement['reste']) &&
    $paiement['reste'] !== null &&
    $paiement['reste'] !== ''
) {
    $reste = (float) $paiement['reste'];
}

$devise = $paiement['devise'];

$nature_dette = $paiement['nature_dete'];

?>

<!DOCTYPE html>

<html lang="fr">
	
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Bordereau de paiement
</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    padding: 30px;

    font-family: Arial, sans-serif;

    background: #f2f2f2;
}

/* =====================================================
   BORDEREAU
===================================================== */

.bordereau {

    width: 800px;

    max-width: 100%;

    margin: auto;

    background: white;

    padding: 40px;

    border: 1px solid #ddd;

    box-shadow:
        0 0 10px
        rgba(0,0,0,0.15);
}

/* =====================================================
   ENTETE
===================================================== */

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

    font-size: 26px;

    color: #333;
}

.entreprise p {

    margin: 5px 0;

    color: #555;
}

.titre {

    text-align: right;
}

.titre h1 {

    margin: 0;

    color: #28a745;

    font-size: 25px;
}

.titre p {

    margin: 5px 0;
}

/* =====================================================
   NUMERO
===================================================== */

.numero {

    text-align: center;

    margin: 20px 0;

    font-size: 18px;
}

.numero strong {

    color: #28a745;
}

/* =====================================================
   INFORMATIONS
===================================================== */

.info {

    margin-top: 20px;

    border: 1px solid #ddd;
}

.ligne {

    display: flex;

    border-bottom: 1px solid #ddd;
}

.ligne:last-child {

    border-bottom: none;
}

.label {

    width: 35%;

    padding: 12px;

    background: #f5f5f5;

    font-weight: bold;
}

.valeur {

    width: 65%;

    padding: 12px;
}

/* =====================================================
   MONTANTS
===================================================== */

.montants {

    margin-top: 25px;
}

.montants table {

    width: 100%;

    border-collapse: collapse;
}

.montants th {

    background: #333;

    color: white;

    padding: 12px;

    border: 1px solid #333;
}

.montants td {

    padding: 12px;

    border: 1px solid #ddd;

    text-align: right;
}

.montants td:first-child {

    text-align: left;
}

.total-paye {

    font-weight: bold;

    font-size: 18px;
}

.reste {

    font-weight: bold;

    font-size: 18px;

    background: #f8f8f8;
}

/* =====================================================
   TEXTE
===================================================== */

.message {

    margin-top: 30px;

    padding: 15px;

    background: #f8f8f8;

    border-left: 4px solid #28a745;

    line-height: 1.6;
}

/* =====================================================
   SIGNATURES
===================================================== */

.signatures {

    display: flex;

    justify-content: space-between;

    margin-top: 70px;

    text-align: center;
}

.signature {

    width: 40%;
}

.signature .trait {

    margin-top: 50px;

    border-top: 1px solid #333;

    padding-top: 8px;
}

/* =====================================================
   BOUTONS
===================================================== */

.actions {

    text-align: center;

    margin-top: 35px;
}

.btn {

    display: inline-block;

    padding: 11px 20px;

    margin: 5px;

    border-radius: 5px;

    color: white;

    text-decoration: none;

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

.btn-imprimer:hover {

    background: #0056b3;
}

/* =====================================================
   IMPRESSION
===================================================== */

@media print {

    body {

        background: white;

        padding: 0;
    }

    .bordereau {

        width: 100%;

        max-width: none;

        border: none;

        box-shadow: none;

        padding: 15px;
    }

    .actions {

        display: none;
    }

}

</style>

</head>

<body>

<div class="bordereau">

<!-- =====================================================
     ENTETE
===================================================== -->

<div class="entete">

    <div class="entreprise">

        <h2>
            CICI
        </h2>

        <p>
            Gestion des dettes
        </p>

        <p>
            Paiement de dettes
        </p>

    </div>

    <div class="titre">

        <h1>
            BORDEREAU
        </h1>

        <p>
            DE PAIEMENT
        </p>

        <p>

            Date :

            <?php

            echo date(
                "d/m/Y",
                strtotime(
                    $paiement['date']
                )
            );

            ?>

        </p>

    </div>

</div>

<!-- =====================================================
     NUMERO
===================================================== -->

<div class="numero">

    N° Bordereau :

    <strong>

        <?php

        echo "BP-"
            . date(
                "Y",
                strtotime(
                    $paiement['date']
                )
            )
            . "-"
            . str_pad(
                $paiement['id_dette_pey'],
                5,
                "0",
                STR_PAD_LEFT
            );

        ?>

    </strong>

</div>

<!-- =====================================================
     INFORMATIONS
===================================================== -->

<div class="info">

    <div class="ligne">

        <div class="label">
            Dette
        </div>

        <div class="valeur">

            <?php

            echo htmlspecialchars(
                $nature_dette
            );

            ?>

        </div>

    </div>

    <div class="ligne">

        <div class="label">
            Date de paiement
        </div>

        <div class="valeur">

            <?php

            echo date(
                "d/m/Y",
                strtotime(
                    $paiement['date']
                )
            );

            ?>

        </div>

    </div>

    <div class="ligne">

        <div class="label">
            Devise
        </div>

        <div class="valeur">

            <?php

            echo htmlspecialchars(
                $devise
            );

            ?>

        </div>

    </div>

</div>

<!-- =====================================================
     MONTANTS
===================================================== -->

<div class="montants">

<table>

<thead>

<tr>

    <th>
        Désignation
    </th>

    <th>
        Montant
    </th>

</tr>

</thead>

<tbody>

<tr>

    <td>
        Montant total de la dette
    </td>

    <td>

        <?php

        echo number_format(
            $montant_dette,
            2,
            ',',
            ' '
        );

        ?>

        <?php
        echo htmlspecialchars($devise);
        ?>

    </td>

</tr>

<tr class="total-paye">

    <td>
        Montant payé
    </td>

    <td>

        <?php

        echo number_format(
            $montant_paye,
            2,
            ',',
            ' '
        );

        ?>

        <?php
        echo htmlspecialchars($devise);
        ?>

    </td>

</tr>

<tr class="reste">

    <td>
        Reste à payer
    </td>

    <td>

        <?php

        echo number_format(
            $reste,
            2,
            ',',
            ' '
        );

        ?>

        <?php
        echo htmlspecialchars($devise);
        ?>

    </td>

</tr>

</tbody>

</table>

</div>

<!-- =====================================================
     MESSAGE
===================================================== -->

<div class="message">

    <strong>
        Objet :
    </strong>

    Ce bordereau confirme le paiement
    effectué au titre de la dette
    mentionnée ci-dessus.

    <br><br>

    <strong>
        Montant payé :
    </strong>

    <?php

    echo number_format(
        $montant_paye,
        2,
        ',',
        ' '
    );

    ?>

    <?php
    echo htmlspecialchars($devise);
    ?>

</div>

<!-- =====================================================
     SIGNATURES
===================================================== -->

<div class="signatures">

    <div class="signature">

        <strong>
            Le responsable
        </strong>

        <div class="trait">
            Signature
        </div>

    </div>

    <div class="signature">

        <strong>
            Le payeur
        </strong>

        <div class="trait">
            Signature
        </div>

    </div>

</div>

<!-- =====================================================
     BOUTONS
===================================================== -->

<div class="actions">

    <button
        onclick="window.print()"
        class="btn btn-imprimer"
    >

        🖨 Imprimer le bordereau

    </button>

    <a
        href="liste.php"
        class="btn btn-retour"
    >

        ← Retour à la liste

    </a>

</div>

</div>

</body>

</html>


