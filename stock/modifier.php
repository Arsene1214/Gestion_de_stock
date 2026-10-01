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
   VERIFICATION DE L'ID
   ============================== */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du stock introuvable.");
}

$id = intval($_GET['id']);


/* ==============================
   MODIFICATION
   ============================== */

if (isset($_POST['modifier'])) {

    $date = $_POST['date'];
    $nom_produit = $_POST['nom_produit'];
    $quantite = $_POST['quantite'];
    $prix_unitaire = $_POST['prix_unitaire'];
    $id_appro = $_POST['id_appro'];
    $id_devise = $_POST['id_devise'];

    $sql = "UPDATE stock SET
                `date` = ?,
                nom_produit = ?,
                quantite = ?,
                prix_unitaire = ?,
                id_appro = ?,
                id_devise = ?
            WHERE id_stock = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Erreur de préparation : " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssidiii",
        $date,
        $nom_produit,
        $quantite,
        $prix_unitaire,
        $id_appro,
        $id_devise,
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
   RECUPERATION DU STOCK
   ============================== */

$sql_stock = "SELECT *
              FROM stock
              WHERE id_stock= ?";

$stmt_stock = mysqli_prepare($conn, $sql_stock);

mysqli_stmt_bind_param(
    $stmt_stock,
    "i",
    $id
);

mysqli_stmt_execute($stmt_stock);

$resultat_stock = mysqli_stmt_get_result($stmt_stock);

if (mysqli_num_rows($resultat_stock) == 0) {
    die("Stock introuvable.");
}

$stock = mysqli_fetch_assoc($resultat_stock);

mysqli_stmt_close($stmt_stock);


/* ==============================
   RECUPERATION DES DEVISES
   ============================== */

$sql_devise = "SELECT id_devise, nom_devise
               FROM devise
               ORDER BY nom_devise ASC";

$resultat_devise = mysqli_query($conn, $sql_devise);

if (!$resultat_devise) {
    die("Erreur de chargement des devises : "
        . mysqli_error($conn));
}


/* ==============================
   RECUPERATION DES APPROVISIONNEMENTS
   ============================== */

$sql_appro = "SELECT id_appro, produit
              FROM approvisionnement
              ORDER BY produit DESC";

$resultat_appro = mysqli_query($conn, $sql_appro);

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Modifier un stock</title>

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
    color: #333;
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

input[type="submit"] {
    background: #007bff;
    color: white;
    border: none;
    padding: 12px;
    margin-top: 25px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
}

input[type="submit"]:hover {
    background: #0056b3;
}

.retour {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #333;
    text-decoration: none;
}

</style>

</head>

<body>

<div class="container">

<h2>Modifier le stock</h2>

<form action="modifier.php?id=<?php echo $id; ?>" method="POST">


<!-- DATE -->

<label>Date du stock :</label>

<input
    type="date"
    name="date"
    value="<?php echo htmlspecialchars($stock['date']); ?>"
    required
>


<!-- PRODUIT -->

<label>Nom du produit :</label>

<input
    type="text"
    name="nom_produit"
    value="<?php echo htmlspecialchars($stock['nom_produit']); ?>"
    required
>


<!-- QUANTITE -->

<label>Quantité :</label>

<input
    type="number"
    name="quantite"
    min="1"
    value="<?php echo htmlspecialchars($stock['quantite']); ?>"
    required
>


<!-- PRIX UNITAIRE -->

<label>Prix unitaire :</label>

<input
    type="number"
    name="prix_unitaire"
    min="0"
    step="0.01"
    value="<?php echo htmlspecialchars($stock['prix_unitaire']); ?>"
    required
>


<!-- APPROVISIONNEMENT -->

<label>Approvisionnement :</label>

<select name="id_appro" required>

    <option value="">
        -- Sélectionner un approvisionnement --
    </option>

    <?php

    if ($resultat_appro) {

        while ($appro = mysqli_fetch_assoc($resultat_appro)) {

            $selected = "";

            if ($appro['id_appro'] == $stock['id_appro']) {
                $selected = "selected";
            }

            echo '<option value="'
                 . $appro['id_appro']
                 . '" '
                 . $selected
                 . '>'
                 . htmlspecialchars($appro['produit'])
                 . '</option>';
        }
    }

    ?>

</select>


<!-- DEVISE -->

<label>Devise :</label>

<select name="id_devise" required>

    <option value="">
        -- Sélectionner une devise --
    </option>

    <?php

    while ($devise = mysqli_fetch_assoc($resultat_devise)) {

        $selected = "";

        if ($devise['id_devise'] == $stock['id_devise']) {
            $selected = "selected";
        }

        echo '<option value="'
             . $devise['id_devise']
             . '" '
             . $selected
             . '>'
             . htmlspecialchars($devise['nom_devise'])
             . '</option>';
    }

    ?>

</select>


<!-- BOUTON -->

<input
    type="submit"
    name="modifier"
    value="Enregistrer les modifications"
>

</form>


<a href="liste.php" class="retour">
    ← Retour à la liste des stocks
</a>

</div>

</body>

</html>
