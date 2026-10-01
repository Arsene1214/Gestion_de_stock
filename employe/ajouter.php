<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

if(isset($_POST['ajouter']))
{
    $date = $_POST['date'];
    $nom = $_POST['nom'];
    $postnom = $_POST['postnom'];
    $prenom = $_POST['prenom'];
    $fonction = $_POST['fonction'];
    $telephone = $_POST['telephone'];
    $mail = $_POST['mail'];
    $salaire = $_POST['salaire'];

    $sql = "INSERT INTO employe
    (date,nom, postnom, prenom, fonction, telephone, mail, salaire)
    VALUES
    ( '$date', '$nom','$postnom','$prenom','$fonction','$telephone','$mail','$salaire')";

if(mysqli_query($conn,$sql))
{
    header("Location: liste.php");
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Ajouter Employe</title>

<style>
body{
    font-family:Arial;
    background:#f4f4f4;
}

.container{
    width:80%;
    margin:auto;
    background:white;
    padding:20px;
    border-radius:10px;
}

input,select,textarea{
    width:100%;
    padding:10px;
    margin-bottom:10px;
}

button{
    background:green;
    color:white;
    border:none;
    padding:10px;
    cursor:pointer;
}
</style>

</head>
<body>

<div class="container">

<h2>Ajouter une Employe</h2>

<form method="POST">

<label>date</label>
<input type="date" name="date">

<label>nom</label>
<input type="text" name="nom">

<label>postnom</label>
<input type="text" name="postnom">

<label>prenom</label>
<input type="text" name="prenom">

<label>fonction</label>
<input type="text" name="fonction">

<label>telephone</label>
<input type="text" name="telephone">

<label>mail</label>
<input type="email" name="mail">

<label>salaire</label>
<input type="number" step="0.01" name="salaire">

<button type="submit" name="ajouter">
Enregistrer
</button>

<a href="liste.php">Voir la liste</a>

</form>

</div>

</body>
</html>


