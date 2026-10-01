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
    die("ID de la vente introuvable.");
}

$id = intval($_GET['id']);


/* ==============================
   MODIFICATION DE LA VENTE
   ============================== */

if (isset($_POST['modifier'])) {

    $produit = $_POST['produit'];
    $date_vente = $_POST['date'];
    $quantite = (int) $_POST['quantite'];
    $PU = (float) $_POST['PU'];

    // Calcul automatique
    $montant = $quantite * $PU;

    $devise = $_POST['devise'];
    $id_article = (int) $_POST['id_article'];
    $id_employe = (int) $_POST['id_employe'];


    $sql = "UPDATE vente SET
                produit = ?,
                `date` = ?,
                quantite = ?,
                PU = ?,
                montant = ?,
                devise = ?,
                id_article = ?,
                id_employe = ?
            WHERE id_article = ?";


    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die(
            "Erreur de préparation : "
            . mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $stmt,
        "ssidssiii",
        $produit,
        $date_vente,
        $quantite,
        $PU,
        $montant,
        $devise,
        $id_article,
        $id_employe,
        $id
    );


    if (mysqli_stmt_execute($stmt)) {

        header("Location: liste.php");
        exit();

    } else {

        echo "Erreur lors de la modification : "
             . mysqli_stmt_error($stmt);
    }


    mysqli_stmt_close($stmt);
}


/* ==============================
   RECUPERATION DE LA VENTE
   ============================== */

$sql_vente = "SELECT *
              FROM vente
              WHERE id_vente = ?";

$stmt_vente = mysqli_prepare(
    $conn,
    $sql_vente
);

mysqli_stmt_bind_param(
    $stmt_vente,"i",
    $id
);

mysqli_stmt_execute($stmt_vente);

$resultat_vente =
    mysqli_stmt_get_result($stmt_vente);


if (mysqli_num_rows($resultat_vente) == 0) {
    die("Vente introuvable.");
}


$vente =
    mysqli_fetch_assoc($resultat_vente);


mysqli_stmt_close($stmt_vente);


/* ==============================
   RECUPERATION DES ARTICLES
   ============================== */

$sql_article = "SELECT
                    id_article,
                    nom_article
                FROM article
                ORDER BY nom_article ASC";

$resultat_article =
    mysqli_query($conn, $sql_article);


if (!$resultat_article) {

    die(
        "Erreur de chargement des articles : "
        . mysqli_error($conn)
    );

}


/* ==============================
   RECUPERATION DES EMPLOYES
   ============================== */

$sql_employe = "SELECT
                    id_employe,
                    nom,
                    postnom,
                    prenom
                FROM employe
                ORDER BY nom ASC";

$resultat_employe =
    mysqli_query($conn, $sql_employe);


if (!$resultat_employe) {

    die(
        "Erreur de chargement des employés : "
        . mysqli_error($conn)
    );

}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Modifier une vente</title>


<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 30px;
}


.container {
    width: 80%;
    max-width: 700px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;

    box-shadow:
        0 0 10px rgba(0,0,0,0.1);
}


h2 {
    text-align: center;
    color: #007bff;
    margin-bottom: 25px;
}


label {
    display: block;
    font-weight: bold;
    margin-top: 15px;
    margin-bottom: 7px;
}


input,
select {

    width: 100%;

    padding: 11px;

    box-sizing: border-box;

    border: 1px solid #ccc;

    border-radius: 5px;

    font-size: 15px;
}


input:focus,
select:focus {

    border-color: #007bff;

    outline: none;
}


input[readonly] {

    background: #f0f0f0;

    font-weight: bold;
}


button {

    width: 100%;

    padding: 12px;

    background: #007bff;

    color: white;

    border: none;

    border-radius: 5px;

    font-size: 16px;

    cursor: pointer;

    margin-top: 25px;

    font-weight: bold;
}


button:hover {

    background: #0056b3;

}


.retour {

    display: block;

    text-align: center;

    margin-top: 15px;

    text-decoration: none;

    color: #333;
}

</style>

</head>


<body>


<div class="container">


<h2>Modifier une vente</h2>


<form
    action="modifier.php?id_vente=<?php echo $id; ?>"
    method="POST"
>


<!-- PRODUIT -->

<label>Produit :</label>

<input
    type="text"
    name="produit"
    value="<?php
        echo htmlspecialchars(
            $vente['produit']
        );
    ?>"
    required
>


<!-- DATE -->

<label>Date de vente :</label>

<input
    type="date"
    name="date"
    value="<?php
        echo htmlspecialchars(
            $vente['date']
        );
    ?>"
    required
>


<!-- QUANTITE -->

<label>Quantité :</label>

<input
    type="number"
    id="quantite"
    name="quantite"
    min="1"
    value="<?php
        echo htmlspecialchars(
            $vente['quantite']
        );
    ?>"
    required
>


<!-- PRIX UNITAIRE -->

<label>Prix unitaire :</label>

<input
    type="number"
    id="PU"
    name="PU"
    step="0.01"
    min="0"
    value="<?php
        echo htmlspecialchars(
            $vente['PU']
        );
    ?>"
    required
>


<!-- MONTANT -->

<label>Montant :</label>

<input
    type="number"
    id="montant"
    name="montant"
    step="0.01"
    value="<?php
        echo htmlspecialchars(
            $vente['montant']
        );
    ?>"
    readonly
>


<!-- DEVISE -->

<label>Devise :</label>

<select
    name="devise"
    required
>

    <option value="">
        -- Sélectionner une devise --
    </option>

    <option
        value="USD"
        <?php
        if ($vente['devise'] == 'USD') {
            echo 'selected';
        }
        ?>
    >
        USD
    </option>

    <option
        value="CDF"
        <?php
        if ($vente['devise'] == 'CDF') {
            echo 'selected';
        }
        ?>
    >
        CDF
    </option>

</select>


<!-- ARTICLE -->

<label>Article :</label>

<select
    name="id_article"
    required
>

    <option value="">
        -- Sélectionner un article --
    </option>


    <?php

    while (
        $article =
        mysqli_fetch_assoc(
            $resultat_article
        )
    ) {

        $selected = "";

        if (
            $article['id_article']
            == $vente['id_article']
        ) {

            $selected = "selected";

        }

    ?>

    <option
        value="<?php
            echo $article['id_article'];
        ?>"
        <?php echo $selected; ?>
    >

        <?php

        echo htmlspecialchars(
            $article['nom_article']
        );

        ?>

    </option>

    <?php

    }

    ?>

</select>


<!-- EMPLOYE -->

<label>Employé :</label>

<select
    name="id_employe"
    required
>

    <option value="">
        -- Sélectionner un employé --
    </option>


    <?php

    while (
        $employe =
        mysqli_fetch_assoc(
            $resultat_employe
        )
    ) {

        $selected = "";

        if (
            $employe['id_employe']
            == $vente['id_employe']
        ) {

            $selected = "selected";

        }


        $nom_complet =
            $employe['nom']
            . " "
            . $employe['postnom']
            . " "
            . $employe['prenom'];

    ?>

    <option
        value="<?php
            echo $employe['id_employe'];
        ?>"
        <?php echo $selected; ?>
    >

        <?php

        echo htmlspecialchars(
            $nom_complet
        );

        ?>

    </option>

    <?php

    }

    ?>

</select>


<!-- BOUTON -->

<button
    type="submit"
    name="modifier"
>

    Enregistrer les modifications

</button>


</form>


<a
    href="liste.php"
    class="retour"
>

    ← Retour à la liste des ventes

</a>


</div>


<script>

function calculerMontant() {

    let qte =
        parseFloat(
            document.getElementById(
                "quantite"
            ).value
        ) || 0;


    let pu =
        parseFloat(
            document.getElementById(
                "PU"
            ).value
        ) || 0;


    let montant = qte * pu;


    document.getElementById(
        "montant"
    ).value =
        montant.toFixed(2);
}


document
    .getElementById("quantite")
    .addEventListener(
        "input",
        calculerMontant
    );


document
    .getElementById("PU")
    .addEventListener(
        "input",
        calculerMontant
    );

</script>


</body>

</html>
