
<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

$id = $_GET['id'];

if(isset($_POST['modifier'])){

    $date = $_POST['date'];
    $nom = $_POST['nom'];
    $postnom = $_POST['postnom'];
    $prenom = $_POST['prenom'];
    $fonction = $_POST['fonction'];
    $telephone = $_POST['telephone'];
    $salaire = $_POST['salaire'];

    mysqli_query($conn,"UPDATE employe SET
    date='$date',
    nom='$nom',
    postnom='$postnom',
    prenom='$prenom',
    fonction='$fonction',
    telephone='$telephone',
    salaire='$salaire'
    WHERE id_employe='$id'");

    header("Location: liste.php");
}

$res = mysqli_query($conn,"SELECT * FROM employe WHERE id_employe='$id'");
$data = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Modifier employé</title>
</head>
<body>

<h2>Modifier employé</h2>

<form method="POST">

Date :<br>
<input type="date" name="date" value="<?php echo $data['date']; ?>" required><br><br>


Nom :<br>
<input type="text" name="nom" value="<?php echo $data['nom']; ?>" required><br><br>

Postnom :<br>
<input type="text" name="postnom" value="<?php echo $data['postnom']; ?>" required><br><br>

Prénom :<br>
<input type="text" name="prenom" value="<?php echo $data['prenom']; ?>" required><br><br>

Fonction :<br>
<input type="text" name="fonction" value="<?php echo $data['fonction']; ?>" required><br><br>

Téléphone :<br>
<input type="text" name="telephone" value="<?php echo $data['telephone']; ?>" required><br><br>

Salaire :<br>
<input type="number" step="0.01" name="salaire" value="<?php echo $data['salaire']; ?>" required><br><br>

<input type="submit" name="modifier" value="Modifier">

</form>

</body>
</html>
