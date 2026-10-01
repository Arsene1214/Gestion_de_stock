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

$message = "";

if (isset($_POST['ajouter'])) {

    $date = $_POST['date'];
    $nom_devise = $_POST['nom_devise'];
    $taux = $_POST['taux'];

    if (empty($date) || empty($nom_devise) || empty($taux)) {

        $message = "Veuillez remplir tous les champs.";

    } else {

        $date = mysqli_real_escape_string($conn, $date);
        $nom_devise = mysqli_real_escape_string($conn, $nom_devise);
        $taux = mysqli_real_escape_string($conn, $taux);

        $sql = "INSERT INTO devise (date, nom_devise, taux)
                VALUES ('$date', '$nom_devise', '$taux')";

        if (mysqli_query($conn, $sql)) {

            header("Location: liste.php");
            exit();

        } else {

            $message = "Erreur : " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Ajouter une devise</title>

<style>

body {
    font-family: Arial;
    background: #f4f4f4;
}

.container {
    width: 80%;
    margin: 30px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px #ccc;
}

h2 {
    text-align: center;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    box-sizing: border-box;
}

button {
    margin-top: 20px;
    background: green;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: darkgreen;
}

.lien {
    display: inline-block;
    margin-left: 10px;
    padding: 12px 20px;
    background: #333;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.message {
    padding: 10px;
    background: #ffdede;
    color: red;
}

</style>

</head>

<body>

<div class="container">

<h2>Gestion des devises</h2>

<?php

if (!empty($message)) {
    echo "<div class='message'>$message</div>";
}

?>

<form method="POST">

<label>Date du taux</label>

<input
    type="date"
    name="date"
    value="<?php echo date('Y-m-d'); ?>"
    required
>


<label>Devise</label>

<select name="nom_devise" required>

    <option value="">-- Choisir une devise --</option>

    <option value="USD">USD - Dollar américain</option>

    <option value="EUR">EUR - Euro</option>

    <option value="CDF">CDF - Franc congolais</option>

</select>


<label>Taux du jour</label>

<input
    type="number"
    step="0.01"
    name="taux"
    placeholder="Exemple : 2850"
    required
>


<button type="submit" name="ajouter">
    Enregistrer
</button>

<a href="liste.php" class="lien">
    Voir la liste
</a>

</form>

</div>

</body>

</html>
