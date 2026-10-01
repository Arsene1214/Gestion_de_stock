<?php

// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);

// Vérifier la connexion
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Encodage
mysqli_set_charset($conn, "utf8");

// Vérifier si le formulaire a été envoyé
if (isset($_POST['date'], $_POST['nom'])) {

    // Récupération des données
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $mail = mysqli_real_escape_string($conn, $_POST['mail']);

    // Requête d'insertion
    $sql = "INSERT INTO client
            (date, nom, adresse, telephone, mail)
            VALUES
            ('$date', '$nom', '$adresse', '$telephone', '$mail')";

    // Exécution
    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Client enregistré avec succès !');
                window.location.href='liste.php';
              </script>";

    } else {

        echo "Erreur lors de l'enregistrement : " . mysqli_error($conn);
    }
}

mysqli_close($conn);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Gestion des client</title>
<style>

body{
    font-family: Arial, sans-serif;
    width: 700px;
    margin: auto;
}

h2{
    text-align:center;
}

label{
    font-weight:bold;
}

input,select{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    box-sizing:border-box;
}

button{
    background:green;
    color:white;
    border:none;
    padding:12px;
    width:100%;
    cursor:pointer;
}

button:hover{
    background:darkgreen;
}

.lien{
    text-align:center;
    margin-top:15px;
}

</style>

</head>

<body>

<h2>Ajouter un client</h2>

<form action="ajouter.php"method="POST">

Date : 
<input type="date" name="date" required><br><br>

Nom : 
<input type="text" name="nom" required><br><br>

adresse : 
<input type="text" name="adresse"><br><br>

telephone : 
<input type="text" name="telephone"><br><br>

mail : 
<input type="email" name="mail"><br><br>



<input type="submit" value="Enregistrer">

</form>

<br>

<a href="liste.php">Voir la liste des client</a>

</body>
</html>

