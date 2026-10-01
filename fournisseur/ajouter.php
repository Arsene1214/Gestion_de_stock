<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

if(isset($_POST['enregistrer']))
{
    $date = $_POST['date'];
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];

   

   $sql = "INSERT INTO  fournisseur
    (date, nom, adresse, telephone, email)
    VALUES
   ($date, '$nom','$adresse','$telephone','$email')";
    if(mysqli_query($conn,$sql)){  

    header("Location: liste.php");
    exit();
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajout Facture</title>

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

<h2>Enregistrement d'une fournisseur</h2>
<form action="ajouter.php" method="POST">

Date : <br>
<input type="date" name="date" required><br><br>

Nom : <br>
<input type="text" name="nom" required><br><br>

adresse : <br>
<input type="text" name="adresse"><br><br>

telephone : <br>
<input type="text" name="telephone"><br><br>

email : <br>
<input type="email" name="email"><br><br>



<input type="submit" name="enregistrer" value="Enregistrer">

</form>
<div class="lien">
    <a href="liste.php">Voir la liste des factures</a>
</div>

</body>
</html>
