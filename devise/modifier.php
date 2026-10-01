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

if (!isset($_GET['id'])) {
    die("ID de la devise manquant.");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM devise WHERE id_devise = $id";

$resultat = mysqli_query($conn, $sql);

if ($resultat == false) {
    die("erre sal : ".mysqli_error($conn));
}

if (mysqli_num_rows($resultat) == 0) {
    die("Taux introuvable.");
}

$devise = mysqli_fetch_assoc($resultat);

$message = "";

if (isset($_POST['modifier'])) {

    $date = $_POST['date'];
    $nom_devise = $_POST['nom_devise'];
    $taux = $_POST['taux'];

    if (empty($date) || empty($nom_devise) || empty($taux)) {

        $message = "Veuillez remplir tous les champs.";

    } else {

        $date = mysqli_real_escape_string($conn, $date);
        $nom_devise = mysqli_real_escape_string($conn, $nom_devise);
        $taux = mysqli_real_escape_string($conn, $taux);

        $sql_update = "UPDATE devise SET
                       date = '$date',
                       nom_devise = '$nom_devise',
                       taux = '$taux',
                       date_modification = NOW()
                       WHERE id_devise = $id";

        if (mysqli_query($conn, $sql_update)) {

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

<title>Modifier le taux</title>

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
    background: orange;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.retour {
    display: inline-block;
    margin-left: 10px;
    padding: 12px 20px;
    background: #333;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.message {
    background: #ffdede;
    color: red;
    padding: 10px;
}

</style>

</head>

<body>

<div class="container">

<h2>Modifier le taux de change</h2>

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
    value="<?php echo $devise['date']; ?>"
    required
>


<label>Devise</label>

<select name="nom_devise" required>

<option value="USD"
<?php if ($devise['nom_devise'] == 'USD') echo 'selected'; ?>>
USD - Dollar américain
</option>

<option value="EUR"
<?php if ($devise['nom_devise'] == 'EUR') echo 'selected'; ?>>
EUR - Euro
</option>

<option value="CDF"
<?php if ($devise['nom_devise'] == 'CDF') echo 'selected'; ?>>
CDF - Franc congolais
</option>

</select>


<label>Nouveau taux du jour</label>

<input
    type="number"
    step="0.01"
    name="taux"
    value="<?php echo $devise['taux']; ?>"
    required
>


<button type="submit" name="modifier">
    Enregistrer le nouveau taux
</button>

<a href="liste.php" class="retour">
    Retour
</a>

</form>

</div>

</body>

</html>
