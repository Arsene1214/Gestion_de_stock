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


/* ==========================================================
   VÉRIFICATION DE L'ID
========================================================== */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Facture introuvable.");
}

$id = (int) $_GET['id'];


/* ==========================================================
   MODIFICATION
========================================================== */

if (isset($_POST['modifier'])) {

    $designation  = trim($_POST['designation']);
    $date_facture = $_POST['date'];
    $QT           = floatval($_POST['QT']);
    $PU           = floatval($_POST['PU']);
    $devise       = $_POST['devise'];
    $id_vente     = intval($_POST['id_vente']);
    $id_client    = intval($_POST['id_client']);
    $id_employe   = intval($_POST['id_employe']);

    /* Calcul automatique */
    $total = $QT * $PU;


    /* Requête de modification */

    $sql_update = "
        UPDATE facture
        SET
            date = '$date_facture',
            desingation = '$designation',
            QT = '$QT',
            PU = '$PU',
            total = '$total',
            devise = '$devise',
            id_vente = '$id_vente',
            id_client = '$id_client',
            id_employe = '$id_employe'
        WHERE id_facture = '$id'
    ";


    if (mysqli_query($conn, $sql_update)) {

        header("Location: Liste.php?message=modifie");
        exit;

    } else {

        $erreur = "Erreur lors de la modification : " . mysqli_error($conn);
    }
}


/* ==========================================================
   RÉCUPÉRATION DE LA FACTURE
========================================================== */

$sql_facture = "
    SELECT *
    FROM facture
    WHERE id_facture = '$id'
";

$resultat_facture = mysqli_query($conn, $sql_facture);

if (!$resultat_facture || mysqli_num_rows($resultat_facture) == 0) {
    die("Facture introuvable.");
}

$facture = mysqli_fetch_assoc($resultat_facture);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Modifier facture</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        /* Total automatique */

        #total {
            background: #eeeeee;
            font-weight: bold;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #218838;
        }

        .erreur {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
        }

    </style>

</head>


<body>


<div class="container">

    <h2>MODIFIER LA FACTURE</h2>


    <?php if (isset($erreur)) { ?>

        <div class="erreur">
            <?php echo htmlspecialchars($erreur); ?>
        </div>

    <?php } ?>


    <form method="POST">


        <!-- DATE -->

        <label>Date :</label>

        <input
            type="date"
            name="date"
            value="<?php echo htmlspecialchars($facture['date']); ?>"
            required
        >


        <!-- DESIGNATION -->

        <label>Désignation :</label>

        <input
            type="text"
            name="designation"
            value="<?php echo htmlspecialchars($facture['desingation']); ?>"
            required
        >


        <!-- QUANTITE -->

        <label>Quantité :</label>

        <input
            type="number"
            id="qt"
            name="QT"
            min="1"
            step="0.01"
            value="<?php echo htmlspecialchars($facture['QT']); ?>"
            required
        >


        <!-- PRIX UNITAIRE -->

        <label>Prix unitaire :</label>

        <input
            type="number"
            id="pu"
            name="PU"
            min="0"
            step="0.01"
            value="<?php echo htmlspecialchars($facture['PU']); ?>"
            required
        >


        <!-- TOTAL AUTOMATIQUE -->

        <label>Total :</label>

        <input
            type="number"
            id="total"
            name="total"
            step="0.01"
            value="<?php echo htmlspecialchars($facture['total']); ?>"
            readonly
        >


        <!-- DEVISE -->

        <label>Devise :</label>

        <select name="devise" required>

            <option
                value="USD"
                <?php
                if ($facture['devise'] == 'USD') {
                    echo 'selected';
                }
                ?>
            >
                USD
            </option>

            <option
                value="CDF"
                <?php
                if ($facture['devise'] == 'CDF') {
                    echo 'selected';
                }
                ?>
            >
                CDF
            </option>

        </select>


        <!-- VENTE -->

        <label>Vente :</label>

        <select name="id_vente" required>

            <option value="">
                -- Sélectionner une vente --
            </option>


            <?php

            $sql_vente = "
                SELECT id_vente, produit
                FROM vente
                ORDER BY produit ASC
            ";

            $resultat_vente = mysqli_query($conn, $sql_vente);

            if ($resultat_vente) {

                while ($vente = mysqli_fetch_assoc($resultat_vente)) {

                    ?>

                    <option
                        value="<?php echo $vente['id_vente']; ?>"
                        <?php
                        if ($vente['id_vente'] == $facture['id_vente']) {
                            echo "selected";
                        }
                        ?>
                    >

                        <?php echo htmlspecialchars($vente['produit']); ?>

                    </option>

                    <?php
                }

            } else {

                ?>

                <option value="">
                    Erreur de chargement des ventes
                </option>

                <?php
            }

            ?>

        </select>


        <!-- CLIENT -->

        <label>Client :</label>

        <select name="id_client" required>

            <option value="">
                -- Sélectionner un client --
            </option>


            <?php

            $sql_client = "
                SELECT id_client, nom
                FROM client
                ORDER BY nom ASC
            ";

            $resultat_client = mysqli_query($conn, $sql_client);

            if ($resultat_client) {

                while ($client = mysqli_fetch_assoc($resultat_client)) {

                    ?>

                    <option
                        value="<?php echo $client['id_client']; ?>"
                        <?php
                        if ($client['id_client'] == $facture['id_client']) {
                            echo "selected";
                        }
                        ?>
                    >

                        <?php echo htmlspecialchars($client['nom']); ?>

                    </option>

                    <?php
                }

            } else {

                ?>

                <option value="">
                    Erreur de chargement des clients
                </option>

                <?php
            }

            ?>

        </select>


        <!-- EMPLOYE -->

        <label>Employé :</label>

        <select name="id_employe" required>

            <option value="">
                -- Sélectionner un employé --
            </option>


            <?php

            $sql_employe = "
                SELECT id_employe, nom, postnom, prenom
                FROM employe
                ORDER BY nom ASC
            ";

            $resultat_employe = mysqli_query($conn, $sql_employe);

            if ($resultat_employe) {

                while ($employe = mysqli_fetch_assoc($resultat_employe)) {

                    $nom_complet =
                        $employe['nom'] . " " .
                        $employe['postnom'] . " " .
                        $employe['prenom'];

                    ?>

                    <option
                        value="<?php echo $employe['id_employe']; ?>"
                        <?php
                        if ($employe['id_employe'] == $facture['id_employe']) {
                            echo "selected";
                        }
                        ?>
                    >

                        <?php echo htmlspecialchars($nom_complet); ?>

                    </option>

                    <?php
                }

            } else {

                ?>

                <option value="">
                    Erreur de chargement des employés
                </option>

                <?php
            }

            ?>

        </select>


        <!-- BOUTON -->

        <button type="submit" name="modifier">
            Enregistrer les modifications
        </button>


    </form>

</div>


<script>

/* ==========================================================
   CALCUL AUTOMATIQUE DU TOTAL
   TOTAL = QUANTITÉ × PRIX UNITAIRE
========================================================== */

function calculerTotal() {

    let qt = parseFloat(document.getElementById("qt").value) || 0;

    let pu = parseFloat(document.getElementById("pu").value) || 0;

    let total = qt * pu;

    document.getElementById("total").value = total.toFixed(2);
}


/* Calcul lorsque la quantité change */

document.getElementById("qt").addEventListener(
    "input",
    calculerTotal
);


/* Calcul lorsque le prix unitaire change */

document.getElementById("pu").addEventListener(
    "input",
    calculerTotal
);


/* Calcul automatique au chargement */

calculerTotal();

</script>


</body>

</html>

