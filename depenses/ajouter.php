<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// ======================================================
// AJOUT D'UNE DEPENSE
// ======================================================

$message = "";

if (isset($_POST['ajouter'])) {

    // Récupération des données
    $date_depense       = $_POST['date']. "";
    $categorie          = $_POST['categorie']. "";
    $montant            = $_POST['montant']. 0;
    $devise             = $_POST['devise'] ."USD";
    $taux_change        = $_POST['taux_change']. 1;
    $montant_converti   = $_POST['montant_converti']. 0;
    $mode_paiement      = $_POST['mode_paiement'] . "";
    $beneficiaire       = $_POST['beneficiaire'].  "";
    $reference_paiement = $_POST['reference_paiement'].  "";
    $description        = $_POST['description'] . "";
    $justificatif       = $_POST['justificatif'].  "";
    $statut             = $_POST['statut'] . "VALIDE";
    $id_employe         = $_POST['id_employe'] . "";
    $id_caisse          = $_POST['id'] . "";

    // Vérification
    if (
        empty($date_depense) ||
        empty($categorie) ||
        empty($montant) ||
        empty($id_employe) ||
        empty($id_caisse)
    ) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {

        // Protection contre les injections SQL
        $date_depense       = mysqli_real_escape_string($conn, $date_depense);
        $categorie          = mysqli_real_escape_string($conn, $categorie);
        $montant            = floatval($montant);
        $devise             = mysqli_real_escape_string($conn, $devise);
        $taux_change        = floatval($taux_change);
        $montant_converti   = floatval($montant_converti);
        $mode_paiement      = mysqli_real_escape_string($conn, $mode_paiement);
        $beneficiaire       = mysqli_real_escape_string($conn, $beneficiaire);
        $reference_paiement = mysqli_real_escape_string($conn, $reference_paiement);
        $description        = mysqli_real_escape_string($conn, $description);
        $justificatif       = mysqli_real_escape_string($conn, $justificatif);
        $statut             = mysqli_real_escape_string($conn, $statut);
        $id_employe         = intval($id_employe);
        $id_caisse          = intval($id_caisse);

        // Requête d'insertion
        $sql = "INSERT INTO depenses (
                    date,
                    categorie,
                    montant,
                    devise,
                    taux_change,
                    montant_converti,
                    mode_paiement,
                    beneficiaire,
                    reference_paiement,
                    description,
                    justificatif,
                    statut,
                    id_employe,
                    id
                ) VALUES (
                    '$date_depense',
                    '$categorie',
                    '$montant',
                    '$devise',
                    '$taux_change',
                    '$montant_converti',
                    '$mode_paiement',
                    '$beneficiaire',
                    '$reference_paiement',
                    '$description',
                    '$justificatif',
                    '$statut',
                    '$id_employe',
                    '$id_caisse'
                )";

        if (mysqli_query($conn, $sql)) {

            // Redirection vers la liste
            header("Location: liste.php");
            exit();

        } else {

            $message = "Erreur lors de l'enregistrement : " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Ajouter une dépense</title>

<style>

* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.container {
    width: 80%;
    max-width: 900px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 25px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #444;
}

input,
select,
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 15px;
}

textarea {
    height: 100px;
    resize: vertical;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #28a745;
    outline: none;
}

button {
    background: #28a745;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background: #218838;
}

.lien {
    display: inline-block;
    margin-left: 10px;
    background: #007bff;
    color: white;
    text-decoration: none;
    padding: 12px 20px;
    border-radius: 5px;
}

.lien:hover {
    background: #0056b3;
}

.erreur {
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.section {
    margin-top: 20px;
    margin-bottom: 10px;
    padding: 10px;
    background: #eee;
    font-weight: bold;
    border-left: 4px solid #28a745;
}

@media(max-width: 700px) {

    .container {
        width: 100%;
        padding: 15px;
    }

    button,
    .lien {
        display: block;
        width: 100%;
        text-align: center;
        margin: 5px 0;
    }
}

</style>

</head>

<body>

<div class="container">

<h2>Ajouter une Dépense</h2>

<?php if (!empty($message)) { ?>

    <div class="erreur">
        <?php echo htmlspecialchars($message); ?>
    </div>

<?php } ?>


<form method="POST">

<!-- ==================================================
     INFORMATIONS DE LA DEPENSE
================================================== -->

<div class="section">
    Informations de la dépense
</div>

<label>Date dépense</label>

<input
    type="date"
    name="date"
    value="<?php echo date('Y-m-d'); ?>"
    required
>


<label>Catégorie</label>

<input
    type="text"
    name="categorie"
    placeholder="Ex : Transport, Salaire, Fournitures..."
    required
>


<label>Montant</label>

<input
    type="number"
    step="0.01"
    name="montant"
    placeholder="0.00"
    required
>


<label>Devise</label>

<select name="devise" required>

    <option value="USD">USD</option>

    <option value="CDF">CDF</option>

</select>


<label>Taux de change</label>

<input
    type="number"
    step="0.01"
    name="taux_change"
    value="1"
>


<label>Montant converti</label>

<input
    type="number"
    step="0.01"
    name="montant_converti"
    placeholder="0.00"
>


<!-- ==================================================
     PAIEMENT
================================================== -->

<div class="section">
    Informations du paiement
</div>


<label>Mode de paiement</label>

<select name="mode_paiement" required>

    <option value="ESPECES">
        ESPÈCES
    </option>

    <option value="MOBILE_MONEY">
        MOBILE MONEY
    </option>

    <option value="BANQUE">
        BANQUE
    </option>

</select>


<label>Bénéficiaire</label>

<input
    type="text"
    name="beneficiaire"
    placeholder="Nom du bénéficiaire"
>


<label>Référence paiement</label>

<input
    type="text"
    name="reference_paiement"
    placeholder="Référence"
>


<label>Description</label>

<textarea
    name="description"
    placeholder="Description de la dépense..."
></textarea>


<label>Justificatif</label>

<input
    type="text"
    name="justificatif"
    placeholder="N° ou nom du justificatif"
>


<label>Statut</label>

<select name="statut">

    <option value="VALIDE">
        VALIDE
    </option>

    <option value="ANNULE">
        ANNULE
    </option>

</select>


<!-- ==================================================
     EMPLOYE
================================================== -->

<div class="section">
    Employé responsable
</div>

<label>Employé</label>

<select name="id_employe" required>

    <option value="">
        -- Sélectionner un employé --
    </option>

<?php

$sql_employe = "
    SELECT id_employe, nom, postnom, prenom
    FROM employe
    ORDER BY nom ASC
";

$resultat_employe = mysqli_query($conn, $sql_employe);

if ($resultat_employe) {

    while ($employe = mysqli_fetch_assoc($resultat_employe)) {

        $nom_complet =
            $employe['nom'] . " " .
            $employe['postnom'] . " " .
            $employe['prenom'];

?>

    <option value="<?php echo $employe['id_employe']; ?>">

        <?php echo htmlspecialchars($nom_complet); ?>

    </option>

<?php

    }

} else {

?>

    <option value="">
        Erreur de chargement des employés
    </option>

<?php

}

?>

</select>


<!-- ==================================================
     CAISSE
================================================== -->

<div class="section">
    Opération de caisse
</div>

<label>Caisse</label>

<select name="id" required>

    <option value="">
        -- Sélectionner une opération de caisse --
    </option>

<?php

$sql_caisse = "
    SELECT id, date, type_operation, categorie
    FROM caisse
    ORDER BY date DESC
";

$resultat_caisse = mysqli_query($conn, $sql_caisse);

if ($resultat_caisse) {

    while ($caisse = mysqli_fetch_assoc($resultat_caisse)) {

        $description_caisse =
            $caisse['date'] . " - " .
            $caisse['type_operation'] . " - " .
            $caisse['categorie'];

?>

    <option value="<?php echo $caisse['id']; ?>">

        <?php echo htmlspecialchars($description_caisse); ?>

    </option>

<?php

    }

} else {

?>

    <option value="">
        Erreur de chargement des caisses
    </option>

<?php

}

?>

</select>


<!-- ==================================================
     BOUTONS
================================================== -->

<button type="submit" name="ajouter">
    Enregistrer la dépense
</button>

<a href="liste.php" class="lien">
    Voir la liste
</a>

</form>

</div>

</body>
</html>
