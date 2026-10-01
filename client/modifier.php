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

// Vérifier l'ID
if (!isset($_GET['id'])) {
    die("ID du client introuvable.");
}

$id = intval($_GET['id']);

// Récupérer le client
$sql = "SELECT * FROM client WHERE id_client = $id";
$resultat = mysqli_query($conn, $sql);

if (!$resultat) {
    die("Erreur : " . mysqli_error($conn));
}

if (mysqli_num_rows($resultat) == 0) {
    die("Client introuvable.");
}

$client = mysqli_fetch_assoc($resultat);

// Modification
if (isset($_POST['modifier'])) {

    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $mail = mysqli_real_escape_string($conn, $_POST['mail']);

    $sql_update = "UPDATE client SET
                    date = '$date',
                    nom = '$nom',
                    adresse = '$adresse',
                    telephone = '$telephone',
                    mail = '$mail'
                   WHERE id_client = $id";

    if (mysqli_query($conn, $sql_update)) {

        echo "<script>
                alert('Client modifié avec succès !');
                window.location.href='liste.php';
              </script>";

        exit;

    } else {

        echo "Erreur lors de la modification : " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="utf-8">

<title>Modifier un client</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 30px;
}

.formulaire {
    width: 500px;
    max-width: 100%;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 8px;
}

h2 {
    text-align: center;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    box-sizing: border-box;
}

button {
    margin-top: 20px;
    padding: 10px 20px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.retour {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 15px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

</style>

</head>

<body>

<div class="formulaire">

<h2>Modifier le client</h2>

<form method="POST" action="">

<label>Date :</label>

<input
    type="date"
    name="date"
    value="<?php echo htmlspecialchars($client['date']); ?>"
    required
>

<label>Nom :</label>

<input
    type="text"
    name="nom"
    value="<?php echo htmlspecialchars($client['nom']); ?>"
    required
>

<label>Adresse :</label>

<input
    type="text"
    name="adresse"
    value="<?php echo htmlspecialchars($client['adresse']); ?>"
>

<label>Téléphone :</label>

<input
    type="text"
    name="telephone"
    value="<?php echo htmlspecialchars($client['telephone']); ?>"
>

<label>Mail :</label>

<input
    type="email"
    name="mail"
    value="<?php echo htmlspecialchars($client['mail']); ?>"
>

<button type="submit" name="modifier">
    Enregistrer les modifications
</button>

</form>

<a href="liste.php" class="retour">
    ← Retour à la liste
</a>

</div>

</body>

</html>

<?php

mysqli_close($conn);

?>
