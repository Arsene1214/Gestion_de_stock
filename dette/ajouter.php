<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");
if(isset($_POST['ajouter']))
{
    $date_depense = $_POST['date'];
    $categorie = $_POST['montant_dette'];
    $devi = $_POST['devise'];
    $montant = $_POST['nature_dete'];
    $taux_change = $_POST['id_employe'];
   

    $sql = "INSERT INTO dette(
    date, montant_dette, devise, nature_dete, id_employe)
    VALUES(
    '$date_depense', '$categorie', '$devi', '$montant', '$taux_change')";

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
<title>Ajouter Dette</title>

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

<h2>Ajouter une Détte</h2>

<form method="POST">

<label>date</label>
<input type="date" name="date" required>

<label>montant_dette</label>  total_dette
<input type="number" step="0.01" name="montant_dette">

<label>Devise</label>
<select name="devise">
    <option value="USD">USD</option>
    <option value="CDF">CDF</option>
</select>
<label>nature_dete</label>
<input type="text" name="nature_dete">

<label>id_employe</label>
<input type="number" name="id_employe">

<button type="submit" name="ajouter">
Enregistrer
</button>

<a href="liste.php">Voir la liste</a>

</form>

</div>

</body>
</html>
