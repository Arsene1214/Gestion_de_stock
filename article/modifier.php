<?php
session_start();

/* ==========================================================
   CONNEXION À LA BASE DE DONNÉES
   ========================================================== */

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


/* ==========================================================
   VÉRIFICATION DE L'ID
   ========================================================== */

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    die("ID de l'article introuvable.");
}

$id = isset($_POST['id'])
    ? intval($_POST['id'])
    : intval($_GET['id']);

if ($id <= 0) {
    die("ID de l'article invalide.");
}


/* ==========================================================
   MODIFICATION DE L'ARTICLE
   ========================================================== */

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = mysqli_real_escape_string(
        $conn,
        $_POST['date']
    );

    $nom_article = mysqli_real_escape_string(
        $conn,
        $_POST['nom_article']
    );

    $nombre_article = intval(
        $_POST['nombre_article']
    );

    $prix_article = floatval(
        $_POST['prix_article']
    );

    $id_stock = intval(
        $_POST['id_stock']
    );

    $id_devise = intval(
        $_POST['id_devise']
    );


    /* ------------------------------------------------------
       Vérification
       ------------------------------------------------------ */

    if (
        empty($date) ||
        empty($nom_article) ||
        $nombre_article <= 0 ||
        $prix_article < 0 ||
        $id_stock <= 0 ||
        $id_devise <= 0
    ) {

        $message =
            "Veuillez remplir correctement tous les champs.";

    } else {

        /* --------------------------------------------------
           MODIFICATION
           -------------------------------------------------- */

        $sql = "
            UPDATE article
            SET
                date = '$date',
                nom_article = '$nom_article',
                nombre_article = '$nombre_article',
                prix_article = '$prix_article',
                id_stock = '$id_stock',
                id_devise = '$id_devise'
            WHERE id_article = '$id'
        ";

        if (mysqli_query($conn, $sql)) {

            header("Location: liste.php");
            exit();

        } else {

            $message =
                "Erreur lors de la modification : "
                . mysqli_error($conn);
        }
    }
}


/* ==========================================================
   RÉCUPÉRATION DE L'ARTICLE
   ========================================================== */

$sql_article = "
    SELECT
        id_article,
        date,
        nom_article,
        nombre_article,
        prix_article,
        id_employe,
        id_stock,
        id_devise
    FROM article
    WHERE id_article = '$id'
";

$resultat_article = mysqli_query(
    $conn,
    $sql_article
);

if (!$resultat_article) {
    die(
        "Erreur SQL : "
        . mysqli_error($conn)
    );
}

if (mysqli_num_rows($resultat_article) == 0) {
    die("Article introuvable.");
}

$article = mysqli_fetch_assoc(
    $resultat_article
);


/* ==========================================================
   VALEURS DE L'ARTICLE
   ========================================================== */

$date_article =
    $article['date'];

$nom_article =
    $article['nom_article'];

$nombre_article =
    $article['nombre_article'];

$prix_article =
    $article['prix_article'];

$id_stock_article =
    $article['id_stock'];

$id_devise_article =
    $article['id_devise'];

?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="utf-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Modifier un article</title>


<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 30px;
}

.container {
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 25px;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
}

input,
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input:focus,
select:focus {
    border-color: #007bff;
    outline: none;
}

.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 5px;
    background: #28a745;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.btn:hover {
    background: #218838;
}

.message {
    background: #ffe5e5;
    color: #b30000;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.lien {
    display: block;
    text-align: center;
    margin-top: 20px;
    text-decoration: none;
    color: #007bff;
}

.lien:hover {
    text-decoration: underline;
}

</style>

</head>


<body>


<div class="container">


<h2>Modifier l'article</h2>


<?php

if (!empty($message)) {

    echo '<div class="message">'
        . htmlspecialchars($message)
        . '</div>';
}

?>


<form
    action="modifier.php"
    method="POST"
>


<!-- ID -->

<input
    type="hidden"
    name="id"
    value="<?php echo $id; ?>"
>


<!-- DATE -->

<label for="date">
    Date :
</label>

<input
    type="date"
    id="date"
    name="date"
    value="<?php echo htmlspecialchars($date_article); ?>"
    required
>


<!-- NOM ARTICLE -->

<label for="nom_article">
    Nom de l'article :
</label>

<input
    type="text"
    id="nom_article"
    name="nom_article"
    value="<?php echo htmlspecialchars($nom_article); ?>"
    required
>


<!-- NOMBRE -->

<label for="nombre_article">
    Nombre :
</label>

<input
    type="number"
    id="nombre_article"
    name="nombre_article"
    min="1"
    value="<?php echo $nombre_article; ?>"
    required
>


<!-- PRIX -->

<label for="prix_article">
    Prix :
</label>

<input
    type="number"
    id="prix_article"
    name="prix_article"
    min="0"
    step="0.01"
    value="<?php echo $prix_article; ?>"
    required
>


<!-- STOCK -->

<label for="id_stock">
    Stock :
</label>

<select
    name="id_stock"
    id="id_stock"
    required
>

<option value="">
    -- Sélectionner un stock --
</option>


<?php

$sql_stock = "
    SELECT
        id_stock,
        nom_produit
    FROM stock
    ORDER BY nom_produit ASC
";

$resultat_stock = mysqli_query(
    $conn,
    $sql_stock
);

if ($resultat_stock) {

    while ($stock = mysqli_fetch_assoc(
        $resultat_stock
    )) {

        $selected =
            ($stock['id_stock'] == $id_stock_article)
            ? "selected"
            : "";

        ?>

        <option
            value="<?php echo $stock['id_stock']; ?>"
            <?php echo $selected; ?>
        >

            <?php
            echo htmlspecialchars(
                $stock['nom_produit']
            );
            ?>

        </option>

        <?php
    }

} else {

    echo '
        <option value="">
            Erreur de chargement des stocks
        </option>
    ';
}

?>

</select>


<!-- DEVISE -->

<label for="id_devise">
    Devise :
</label>

<select
    name="id_devise"
    id="id_devise"
    required
>

<option value="">
    -- Sélectionner une devise --
</option>


<?php

$sql_devise = "
    SELECT
        id_devise,
        nom_devise
    FROM devise
    ORDER BY nom_devise ASC
";

$resultat_devise = mysqli_query(
    $conn,
    $sql_devise
);

if ($resultat_devise) {

    while ($devise = mysqli_fetch_assoc(
        $resultat_devise
    )) {

        $selected =
            ($devise['id_devise'] == $id_devise_article)
            ? "selected"
            : "";

        ?>

        <option
            value="<?php echo $devise['id_devise']; ?>"
            <?php echo $selected; ?>
        >

            <?php
            echo htmlspecialchars(
                $devise['nom_devise']
            );
            ?>

        </option>

        <?php
    }

} else {

    echo '
        <option value="">
            Erreur de chargement des devises
        </option>
    ';
}

?>

</select>


<!-- BOUTON -->

<input
    type="submit"
    value="Modifier l'article"
    class="btn"
>


</form>


<a
    href="liste.php"
    class="lien"
>
    ← Retour à la liste des articles
</a>


</div>


</body>

</html>
