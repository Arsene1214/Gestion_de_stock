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
   ENREGISTRER LA VENTE
   ============================== */

if (isset($_POST['enregistrer'])) {

    $produit = $_POST['produit'];
    $date_vente = $_POST['date'];
    $quantite = $_POST['quantite'];
    $PU = $_POST['PU'];
    $montant = $quantite * $PU;
    $devise = $_POST['devise'];
    $id_article = $_POST['id_article'];
    $id_employe = $_POST['id_employe'];


    $sql = "INSERT INTO vente
            (produit, `date`, quantite, PU, montant, devise, id_article, id_employe)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Erreur de préparation : " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssidssii",
        $produit,
        $date_vente,
        $quantite,
        $PU,
        $montant,
        $devise,
        $id_article,
        $id_employe
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: liste.php");
        exit();

    } else {

        echo "Erreur lors de l'enregistrement : "
             . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}


/* ==============================
   RECUPERATION DES ARTICLES
   ============================== */

$sql_article = "SELECT *
                FROM article
                ORDER BY nom_article DESC";

$resultat_article = mysqli_query($conn, $sql_article);

if (!$resultat_article) {
    die("Erreur de chargement des articles : "
        . mysqli_error($conn));
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

$resultat_employe = mysqli_query($conn, $sql_employe);

if (!$resultat_employe) {
    die("Erreur de chargement des employés : "
        . mysqli_error($conn));
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Ajouter une vente</title>

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
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #28a745;
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
    border-color: #28a745;
    outline: none;
}

input[readonly] {
    background: #f0f0f0;
    font-weight: bold;
}

button {
    width: 100%;
    padding: 12px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 25px;
    font-weight: bold;
}

button:hover {
    background: #218838;
}

a {
    display: block;
    text-align: center;
    margin-top: 15px;
    text-decoration: none;
    color: #007bff;
}

</style>

</head>

<body>

<div class="container">

<h2>Ajouter une vente</h2>

<form action="ajouter.php" method="POST">


<!-- PRODUIT -->

<label>Produit :</label>

<input
    type="text"
    name="produit"
    placeholder="Nom du produit"
    required
>


<!-- DATE -->

<label>Date de vente :</label>

<input
    type="date"
    name="date"
    required
>


<!-- QUANTITE -->

<label>Quantité :</label>

<input
    type="number"
    id="quantite"
    name="quantite"
    min="1"
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
    required
>


<!-- MONTANT -->

<label>Montant :</label>

<input
    type="number"
    id="montant"
    name="montant"
    step="0.01"
    readonly
>


<!-- DEVISE -->

<label>Devise :</label>

<select name="devise" required>

    <option value="">
        -- Sélectionner une devise --
    </option>

    <option value="USD">
        USD
    </option>

    <option value="CDF">
        CDF
    </option>

</select>


<!-- ARTICLE -->

<label>Article :</label>

<select name="id_article" required>

    <option value="">
        -- Sélectionner un article --
    </option>

    <?php

    while ($article = mysqli_fetch_assoc($resultat_article)) {

        /*
         * Adapte le champ nom_article si
         * ta table utilise un autre nom.
         */

        $nom_article = "";

        if (isset($article['nom_article'])) {
            $nom_article = $article['nom_article'];

        } elseif (isset($article['designation'])) {
            $nom_article = $article['designation'];

        } elseif (isset($article['nom'])) {
            $nom_article = $article['nom'];

        }

        echo '<option value="'
             . $article['id_article']
             . '">'
             . htmlspecialchars($nom_article)
             . '</option>';
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

    while ($employe = mysqli_fetch_assoc($resultat_employe)) {

        $nom_complet =
            $employe['nom'] . " "
            . $employe['postnom'] . " "
            . $employe['prenom'];

        echo '<option value="'
             . $employe['id_employe']
             . '">'
             . htmlspecialchars($nom_complet)
             . '</option>';
    }

    ?>

</select>


<!-- BOUTON -->

<button
    type="submit"
    name="enregistrer"
>
    Enregistrer
</button>

</form>


<a href="liste.php">
    Voir la liste des ventes
</a>

</div>


<script>

function calculerMontant() {

    let qte =
        parseFloat(
            document.getElementById("quantite").value
        ) || 0;

    let pu =
        parseFloat(
            document.getElementById("PU").value
        ) || 0;

    let montant = qte * pu;

    document.getElementById("montant").value =
        montant.toFixed(2);
}


document
    .getElementById("quantite")
    .addEventListener("input", calculerMontant);


document
    .getElementById("PU")
    .addEventListener("input", calculerMontant);

</script>

</body>

</html>
