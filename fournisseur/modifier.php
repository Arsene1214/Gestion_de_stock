<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

if (!isset($_GET['id'])) {
    die("ID fournisseur manquant !");
}

$id = intval($_GET['id']);

if (isset($_POST['modifier'])) {
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE fournisseur SET
            date='$date',
            nom='$nom',
            adresse='$adresse',
            telephone='$telephone',
            email='$email'
            WHERE id_fournisseur='$id'";

    mysqli_query($conn, $sql);

    header("Location: liste.php");
    exit();
}

$res = mysqli_query($conn, "SELECT * FROM fournisseur WHERE id_fournisseur='$id'");
$data = mysqli_fetch_assoc($res);

if (!$data) {
    die("Fournisseur introuvable !");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modifier Fournisseur</title>
</head>
<body>

<h2>Modifier Fournisseur</h2>

<form method="POST">

    <label>Date :</label><br>
    <input type="date" name="date" value="<?php echo $data['date']; ?>" required>
    <br><br>

    <label>Nom :</label><br>
    <input type="text" name="nom" value="<?php echo $data['nom']; ?>" required>
    <br><br>

    <label>Adresse :</label><br>
    <input type="text" name="adresse" value="<?php echo $data['adresse']; ?>" required>
    <br><br>

    <label>Téléphone :</label><br>
    <input type="text" name="telephone" value="<?php echo $data['telephone']; ?>" required>
    <br><br>

    <label>Email :</label><br>
    <input type="email" name="email" value="<?php echo $data['email']; ?>" required>
    <br><br>

    <input type="submit" name="modifier" value="Modifier">
    <a href="liste.php">Retour</a>

</form>

</body>
</html>
