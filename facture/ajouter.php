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

$message = "";


// ==========================================================
//                    ENREGISTRER FACTURE
// ==========================================================

if (isset($_POST['ajouter'])) {

    $designation  = trim($_POST['designation']);
    $date_facture = $_POST['date'];
    $QT           = floatval($_POST['QT']);
    $PU           = floatval($_POST['PU']);
    $devise       = $_POST['devise'];
    $id_vente     = intval($_POST['id_vente']);
    $id_client    = intval($_POST['id_client']);
    $id_employe   = intval($_POST['id_employe']);

    // Calcul automatique
    $total = $QT * $PU;


    // ======================================================
    // INSERTION
    // ======================================================

    $sql = "
        INSERT INTO facture
        (
            desingation,
            date,
            QT,
            PU,
            total,
            devise,
            id_vente,
            id_client,
            id_employe
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "ssdddsiii",
            $designation,
            $date_facture,
            $QT,
            $PU,
            $total,
            $devise,
            $id_vente,
            $id_client,
            $id_employe
        );

        if (mysqli_stmt_execute($stmt)) {

            $id_facture = mysqli_insert_id($conn);

            header(
                "Location: facture_impression.php?id_facture="
                . $id_facture
            );

            exit();

        } else {

            $message =
                "<div class='error'>
                    Erreur :
                    " .
                    htmlspecialchars(
                        mysqli_stmt_error($stmt)
                    )
                    . "
                </div>";
        }

        mysqli_stmt_close($stmt);

    } else {

        $message =
            "<div class='error'>
                Erreur SQL :
                " .
                htmlspecialchars(
                    mysqli_error($conn)
                )
                . "
            </div>";
    }
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Ajouter une facture</title>


<style>

* {
    box-sizing: border-box;
}

body {

    font-family: Arial, sans-serif;

    background: #f2f2f2;

    margin: 0;

    padding: 20px;
}

.container {

    width: 750px;

    max-width: 100%;

    margin: auto;

    background: white;

    padding: 25px;

    border-radius: 10px;

    box-shadow: 0 0 10px #ccc;
}

h2 {

    text-align: center;

    color: #0066cc;

    margin-bottom: 25px;
}

label {

    display: block;

    font-weight: bold;

    margin-top: 12px;

    margin-bottom: 5px;
}

input,
select {

    width: 100%;

    padding: 10px;

    border: 1px solid #ccc;

    border-radius: 5px;

    font-size: 15px;
}

input:focus,
select:focus {

    outline: none;

    border-color: #0066cc;
}

button {

    width: 100%;

    padding: 12px;

    margin-top: 20px;

    background: green;

    color: white;

    border: none;

    border-radius: 5px;

    font-size: 16px;

    cursor: pointer;
}

button:hover {

    background: darkgreen;
}

.error {

    background: #f8d7da;

    color: #721c24;

    padding: 12px;

    margin-bottom: 15px;

    border-radius: 5px;
}

.info {

    background: #e7f1ff;

    color: #0056b3;

    padding: 10px;

    margin-top: 5px;

    border-radius: 5px;
}

.lien {

    text-align: center;

    margin-top: 20px;
}

.lien a {

    text-decoration: none;

    color: #0066cc;

    font-weight: bold;
}

</style>

</head>


<body>


<div class="container">


<h2>
    Enregistrement d'une facture
</h2>


<?php echo $message; ?>


<form method="POST">

<!-- ======================================================
                     DESIGNATION
======================================================= -->

<label>
    Désignation
</label>

<input
    type="text"
    name="designation"
    id="designation"
    placeholder="Désignation du produit"
    required
>


<!-- ======================================================
                         DATE
======================================================= -->

<label>
    Date de facture
</label>

<input
    type="date"
    name="date"
    value="<?= date('Y-m-d'); ?>"
    required
>


<!-- ======================================================
                       QUANTITÉ
======================================================= -->

<label>
    Quantité
</label>

<input
    type="number"
    name="QT"
    id="qt"
    min="1"
    step="1"
    required
>


<!-- ======================================================
                    PRIX UNITAIRE
======================================================= -->

<label>
    Prix unitaire
</label>

<input
    type="number"
    name="PU"
    id="pu"
    min="0"
    step="0.01"
    required
>


<!-- ======================================================
                         TOTAL
======================================================= -->

<label>
    Total
</label>

<input
    type="number"
    name="total"
    id="total"
    step="0.01"
    readonly
>

<div class="info">

    Le total est calculé automatiquement :

    Quantité × Prix unitaire.

</div>


<!-- ======================================================
                         DEVISE
======================================================= -->

<label>
    Devise
</label>

<select
    name="devise"
    required
>

    <option value="USD">
        USD
    </option>

    <option value="CDF">
        CDF
    </option>

    </select>


<!-- ======================================================
                         Vente
======================================================= -->

<label>
Vente
</label>

<select
    name="id_vente"
    required
>

    <option value="">
        -- Sélectionner un vente --
    </option>


<?php

$sql_client = "
    SELECT
        id_vente,
        produit

    FROM vente

    ORDER BY produit ASC
";

$resultat_client = mysqli_query(
    $conn,
    $sql_client
);


if ($resultat_client) {

    while (
        $client = mysqli_fetch_assoc(
            $resultat_client
        )
    ) {

?>

    <option value="<?php echo $client['id_vente']; ?>">

        <?php echo  htmlspecialchars(
            $client['produit']
        ); ?>

    </option>

<?php

    }

} else {

    echo "
        <option value=''>
            Erreur de chargement des clients
        </option>
    ";
}

?>

</select>




<!-- ======================================================
                         CLIENT
======================================================= -->

<label>
    Client
</label>

<select
    name="id_client"
    required
>

    <option value="">
        -- Sélectionner un client --
    </option>


<?php

$sql_client = "
    SELECT
        id_client,
        nom

    FROM client

    ORDER BY nom ASC
";

$resultat_client = mysqli_query(
    $conn,
    $sql_client
);


if ($resultat_client) {

    while (
        $client = mysqli_fetch_assoc(
            $resultat_client
        )
    ) {

?>

    <option value="<?php echo $client['id_client']; ?>">

        <?php echo  htmlspecialchars(
            $client['nom']
        ); ?>

    </option>

<?php

    }

} else {

    echo "
        <option value=''>
            Erreur de chargement des clients
        </option>
    ";
}

?>

</select>


<!-- ======================================================
                         EMPLOYE
======================================================= -->

<label>
    Employé
</label>

<select
    name="id_employe"
    required
>

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

    <option value="<?php echo $employe['id_employe']; ?>">

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


<!-- ======================================================
                       ENREGISTRER
======================================================= -->

<button
    type="submit"
    name="ajouter"
>

    Enregistrer la facture

</button>


</form>


<div class="lien">

    <a href="liste.php">
        Voir la liste des factures
    </a>

</div>


</div>


<script>

// ==========================================================
//                  CALCUL DU TOTAL
// ==========================================================

function calculerTotal()
{

    let qt =
        parseFloat(
            document.getElementById("qt").value
        ) || 0;

    let pu =
        parseFloat(
            document.getElementById("pu").value
        ) || 0;

    let total = qt * pu;

    document.getElementById("total").value =
        total.toFixed(2);
}


document
    .getElementById("qt")
    .addEventListener(
        "input",
        calculerTotal
    );


document
    .getElementById("pu")
    .addEventListener(
        "input",
        calculerTotal
    );

</script>


</body>

</html>
