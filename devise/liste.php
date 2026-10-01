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


/* ============================
   REQUETE POUR LES DEVISES
   ============================ */

$sql = "SELECT id_devise, date, nom_devise, taux
        FROM devise
        ORDER BY date DESC, id_devise DESC";

$resultat = mysqli_query($conn, $sql);


/* Vérification de la requête */

if (!$resultat) {
    die("Erreur SQL : " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Liste des devises</title>

<style>

body {
    font-family: Arial;
    background: #f4f4f4;
}

.container {
    width: 90%;
    margin: 30px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px #ccc;
}

h2 {
    text-align: center;
}

.ajouter {
    display: inline-block;
    background: green;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #252b3a;
    color: white;
}

.modifier {
    background: orange;
    color: white;
    padding: 7px 12px;
    text-decoration: none;
    border-radius: 5px;
}

</style>

</head>

<body>

<div class="container">

<h2>Gestion des taux de change</h2>

<a href="ajouter.php" class="ajouter">
    + Ajouter un taux
</a>

<table>

<tr>
    <th>ID</th>
    <th>Date</th>
    <th>Devise</th>
    <th>Taux du jour</th>
    <th>Action</th>
</tr>

<?php

if (mysqli_num_rows($resultat) > 0) {

    while ($ligne = mysqli_fetch_assoc($resultat)) {

?>

<tr>

<td>
    <?php echo $ligne['id_devise']; ?>
</td>

<td>
    <?php echo $ligne['date']; ?>
</td>

<td>
    <?php echo $ligne['nom_devise']; ?>
</td>

<td>
    <?php echo number_format($ligne['taux'], 2, ',', ' '); ?>
</td>

<td>

<a
    href="modifier.php?id=<?php echo $ligne['id_devise']; ?>"
    class="modifier"
>
    Modifier
</a>

</td>

</tr>

<?php

    }

} else {

?>

<tr>

<td colspan="5">
    Aucun taux enregistré.
</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>

