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

// Recherche
$recherche = "";

if (isset($_GET['recherche'])) {
    $recherche = mysqli_real_escape_string($conn, $_GET['recherche']);
}

// Requête
$sql = "SELECT * FROM client
        WHERE nom LIKE '%$recherche%'
        OR adresse LIKE '%$recherche%'
        OR telephone LIKE '%$recherche%'
        OR mail LIKE '%$recherche%'
        ORDER BY id_client DESC";

$resultat = mysqli_query($conn, $sql);

if (!$resultat) {
    die("Erreur lors du chargement des clients : " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="utf-8">

<title>Liste des clients</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 20px;
}

h2 {
    text-align: center;
    color: #333;
}

.recherche {
    text-align: center;
    margin-bottom: 20px;
}

.recherche input {
    padding: 10px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.recherche button {
    padding: 10px 15px;
    border: none;
    background: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #007bff;
    color: white;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

.btn-modifier {
    background: #28a745;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
}

.btn-supprimer {
    background: #dc3545;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
}

.btn-ajouter {
    display: inline-block;
    margin-bottom: 15px;
    padding: 10px 15px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

</style>

</head>

<body>

<h2>Liste des clients</h2>

<a href="index.php" class="btn-ajouter">
    + Ajouter un client
</a>

<div class="recherche">

<form method="GET" action="liste.php">

<input
    type="text"
    name="recherche"
    placeholder="Rechercher un client..."
    value="<?php echo htmlspecialchars($recherche); ?>"
>

<button type="submit">Rechercher</button>

</form>

</div>

<table>

<tr>
    <th>ID</th>
    <th>Date</th>
    <th>Nom</th>
    <th>Adresse</th>
    <th>Téléphone</th>
    <th>Mail</th>
    <th>Actions</th>
</tr>

<?php

if (mysqli_num_rows($resultat) > 0) {

    while ($client = mysqli_fetch_assoc($resultat)) {

?>

<tr>

    <td>
        <?php echo $client['id_client']; ?>
    </td>

    <td>
        <?php echo htmlspecialchars($client['date']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($client['nom']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($client['adresse']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($client['telephone']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($client['mail']); ?>
    </td>

    <td>

        <a
            href="modifier.php?id=<?php echo $client['id_client']; ?>"
            class="btn-modifier"
        >
            Modifier
        </a>

        <a
            href="supprimer.php?id=<?php echo $client['id_client']; ?>"
            class="btn-supprimer"
            onclick="return confirm('Voulez-vous vraiment supprimer ce client ?');"
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
    <td colspan="7">
        Aucun client trouvé.
    </td>
</tr>

<?php

}

?>

</table>

</body>
</html>

<?php

mysqli_close($conn);

?>
