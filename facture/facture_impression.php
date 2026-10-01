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

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Facture introuvable.");
}

$id = (int) $_GET['id'];

$sql = "
    SELECT
        facture.*,
        employe.nom,
        employe.postnom,
        employe.prenom
    FROM facture
    LEFT JOIN employe
        ON facture.id_employe = employe.id_employe
    WHERE facture.id_facture = '$id'
";

$resultat = mysqli_query($conn, $sql);

if (!$resultat) {
    die("Erreur SQL : " . mysqli_error($conn));
}

if (mysqli_num_rows($resultat) == 0) {
    die("Facture introuvable.");
}

$facture = mysqli_fetch_assoc($resultat);

$nom_employe =
    $facture['nom'] . " " .
    $facture['postnom'] . " " .
    $facture['prenom'];

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>
Facture N° <?php echo $facture['id_facture']; ?>
</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #eee;
    margin: 0;
    padding: 30px;
}

.facture {
    width: 800px;
    margin: auto;
    background: white;
    padding: 40px;
    box-sizing: border-box;
}

.entete {
    text-align: center;
    border-bottom: 2px solid #000;
    padding-bottom: 15px;
}

.entete h1 {
    margin: 0;
}

.info {
    margin-top: 25px;
}

.info table {
    width: 100%;
}

.info td {
    padding: 5px;
}

table.details {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

.details th,
.details td {
    border: 1px solid #000;
    padding: 12px;
    text-align: center;
}

.details th {
    background: #eee;
}

.total {
    margin-top: 20px;
    text-align: right;
    font-size: 20px;
}

.signature {
    margin-top: 70px;
    display: flex;
    justify-content: space-between;
}

.bouton {
    text-align: center;
    margin-bottom: 20px;
}

.bouton button {
    padding: 10px 20px;
    cursor: pointer;
}

@media print {

    body {
        background: white;
        padding: 0;
    }

    .facture {
        width: 100%;
        padding: 20px;
    }

    .bouton {
        display: none;
    }

}

</style>

</head>

<body>

<div class="bouton">

    <button onclick="window.print()">
        🖨 Imprimer la facture
    </button>

</div>

<div class="facture">

    <div class="entete">

        <h1>FACTURE</h1>

        <p>
            Facture N° :
            <strong>
                <?php echo $facture['id_facture']; ?>
            </strong>
        </p>

    </div>

    <div class="info">

        <table>

            <tr>

                <td>
                    <strong>Date :</strong>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $facture['date']
                    );
                    ?>
                </td>

            </tr>

            <tr>

                <td>
                    <strong>Employé :</strong>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $nom_employe
                    );
                    ?>
                </td>

            </tr>

        </table>

    </div>

    <table class="details">

        <tr>

            <th>desingation</th>

            <th>Quantité</th>

            <th>Prix unitaire</th>

            <th>Total</th>

            <th>Devise</th>

        </tr>

        <tr>

            <td>
                <?php
                echo htmlspecialchars(
                    $facture['desingation']
                );
                ?>
            </td>

            <td>
                <?php echo $facture['QT']; ?>
            </td>

            <td>
                <?php
                echo number_format(
                    $facture['PU'],
                    2,
                    ',',
                    ' '
                );
                ?>
            </td>

            <td>
                <?php
                echo number_format(
                    $facture['total'],
                    2,
                    ',',
                    ' '
                );
                ?>
            </td>

            <td>
                <?php echo $facture['devise']; ?>
            </td>

        </tr>

    </table>

    <div class="total">

        <strong>
            Montant À PAYER :
            <?php
            echo number_format(
                $facture['total'],
                2,
                ',',
                ' '
            );
            ?>
        </strong>

    </div>

    <div class="signature">

        <div>
            Signature de l'employé
            <br><br><br>
            ____________________
        </div>

        <div>
            Signature du client
            <br><br><br>
            ____________________
        </div>

    </div>

</div>

</body>

</html>

