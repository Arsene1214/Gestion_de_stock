
<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

$id = $_GET['id'];

$req = mysqli_query($conn,"SELECT * FROM dette WHERE id_dette='$id'");
$data = mysqli_fetch_assoc($req);

if(isset($_POST['modifier']))
{
    $date_depense = $_POST['date'];
    $categorie = $_POST['montant_dette'];
    $devi = $_POST['devise'];
    $montant = $_POST['nature_dete'];
    $taux_change = $_POST['id_employe'];

    $sql = "UPDATE dette SET

    date='$date_depense',
    montant_dette='$categorie',
    devise='$devi ',
    nature_dete=' $montant',
 
    

    WHERE id_dette='$id'";

    mysqli_query($conn,$sql);

    header("Location: liste.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Modifier Dette</title>

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
    background:blue;
    color:white;
    border:none;
    padding:10px;
}
</style>

</head>
<body>

<div class="container">

<h2>Modifier une Dette</h2>

<form method="POST">

<label>date</label>
<input type="date" name="date"
value="<?php echo $data['date']; ?>">

<label>montant_dette</label>
<input type="number" step="0.01" name="montant_dette"
value="<?php echo $data['montant_dette']; ?>">

<label>Devise</label>
<select name="devise">
    <option value="USD" <?php if($data['devise']=="USD") echo "selected"; ?>>USD</option>
    <option value="CDF" <?php if($data['devise']=="CDF") echo "selected"; ?>>CDF</option>
</select>

<label>nature_dete</label>
<input type="text" name="nature_dete"
value="<?php echo $data['nature_dete']; ?>">


<button type="submit" name="modifier">
Mettre à jour
</button>

</form>

</div>

</body>
</html>
