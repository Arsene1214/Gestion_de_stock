<?php
// ==========================================================
//              AJOUTER UNE OPÉRATION DE CAISSE
// ==========================================================

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

// Connexion à la base de données
$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

$message = "";

// ==========================================================
//              ENREGISTREMENT DE L'OPÉRATION
// ==========================================================

if (isset($_POST['enregistrer'])) {

    // Récupération des données
    $date_operation  = $_POST['date_operation'];
    $type_operation  = $_POST['type_operation'];
    $categorie       = trim($_POST['categorie']);
    $montant         = floatval($_POST['montant']);
    $devise          = $_POST['devise'];
    $taux_change     = floatval($_POST['taux_change']);
    $mode_paiement   = $_POST['mode_paiement'];
    $description     = trim($_POST['description']);
    $id_employe      = intval($_POST['id_employe']);
    $statut          = $_POST['statut'];

    // ======================================================
    // CALCUL DU MONTANT EN CDF
    // ======================================================

    if ($devise == "USD") {

        if ($taux_change <= 0) {
            $taux_change = 1;
        }

        $montant_cdf = $montant * $taux_change;

    } else {

        $taux_change = 1;
        $montant_cdf = $montant;
    }

    // ======================================================
    // RÉFÉRENCE AUTOMATIQUE
    // ======================================================

    $reference_operation = "CAI" . date("YmdHis");

    // ======================================================
    // REQUÊTE D'INSERTION
    // ======================================================

    $sql = "INSERT INTO caisse (
                date_operation,
                type_operation,
                categorie,
                montant,
                devise,
                taux_change,
                montant_cdf,
                mode_paiement,
                reference_operation,
                description,
                id_employe,
                statut
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "sssdsddsssis",
            $date_operation,
            $type_operation,
            $categorie,
            $montant,
            $devise,
            $taux_change,
            $montant_cdf,
            $mode_paiement,
            $reference_operation,
            $description,
            $id_employe,
            $statut
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "
                <div class='success'>
                    ✓ Opération enregistrée avec succès.<br>
                    Référence : <strong>$reference_operation</strong>
                </div>
            ";

        } else {

            $message = "
                <div class='error'>
                    Erreur lors de l'enregistrement :
                    " . htmlspecialchars(mysqli_stmt_error($stmt)) . "
                </div>
            ";
        }

        mysqli_stmt_close($stmt);

    } else {

        $message = "
            <div class='error'>
                Erreur de préparation :
                " . htmlspecialchars(mysqli_error($conn)) . "
            </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ajouter une opération</title>

<style>

* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f2f4f7;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 700px;
    margin: 20px auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.15);
}

h2 {
    text-align: center;
    color: #0066cc;
    margin-bottom: 25px;
}

label {
    display: block;
    font-weight: bold;
    margin-top: 10px;
    margin-bottom: 5px;
}

input,
select,
textarea {
    width: 100%;
    padding: 11px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #0066cc;
    outline: none;
}

textarea {
    resize: vertical;
}

button {
    width: 100%;
    padding: 13px;
    margin-top: 15px;
    background: #008000;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #006600;
}

.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.lien {
    display: block;
    text-align: center;
    margin-top: 20px;
    padding: 11px;
    background: #0066cc;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.lien:hover {
    background: #004999;
}

</style>

</head>

<body>

<div class="container">

<h2>Ajouter une opération de caisse</h2>

<?php
echo $message;
?>

<form method="POST">

    <!-- DATE -->

    <label>Date de l'opération</label>

    <input
        type="date"
        name="date"
        value="<?= date('Y-m-d'); ?>"
        required
    >

    <!-- TYPE -->

    <label>Type d'opération</label>

    <select name="type_operation" required>

        <option value="ENTREE">ENTRÉE</option>

        <option value="SORTIE">SORTIE</option>

    </select>

    <!-- CATEGORIE -->

    <label>Catégorie</label>

    <input
        type="text"
        name="categorie"
        placeholder="Ex : Vente, Achat, Salaire..."
        required
    >

    <!-- MONTANT -->

    <label>Montant</label>

    <input
        type="number"
        step="0.01"
        min="0"
        id="montant"
        name="montant"
        placeholder="0.00"
        required
    >

    <!-- DEVISE -->

    <label>Devise</label>

    <select
        name="devise"
        id="devise"
        required
    >

        <option value="USD">USD</option>

        <option value="CDF">CDF</option>

    </select>

    <!-- TAUX -->

    <label>Taux de change</label>

    <input
        type="number"
        step="0.01"
        min="0"
        id="taux_change"
        name="taux_change"
        value="2850"
        required
    >

    <!-- MONTANT CDF -->

    <label>Montant en CDF</label>

    <input
        type="number"
        step="0.01"
        id="montant_cdf"
        readonly
    >

    <!-- MODE DE PAIEMENT -->

    <label>Mode de paiement</label>

    <select name="mode_paiement" required>

        <option value="Espèces">Espèces</option>

        <option value="Mobile Money">Mobile Money</option>

        <option value="Banque">Banque</option>

        <option value="Carte bancaire">Carte bancaire</option>

        <option value="Chèque">Chèque</option>

    </select>

    <!-- DESCRIPTION -->

    <label>Description</label>

    <textarea
        name="description"
        rows="4"
        placeholder="Description de l'opération..."
    ></textarea>

    <!-- EMPLOYE -->

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

        $resultat = mysqli_query($conn, $sql_employe);

        if ($resultat) {

            while ($employe = mysqli_fetch_assoc($resultat)) {

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

            echo '<option value="">Erreur de chargement des employés</option>';

        }

        ?>

    </select>

    <!-- STATUT -->

    <label>Statut</label>

    <select name="statut" required>

        <option value="EN ATTENTE">
            EN ATTENTE
        </option>

        <option value="VALIDÉ">
            VALIDÉ
        </option>

    </select>

    <!-- BOUTON -->

    <button
        type="submit"
        name="enregistrer"
    >
        Enregistrer l'opération
    </button>

</form>

<a
    href="liste.php"
    class="lien"
>
    Voir la liste des opérations
</a>

</div>


<script>

// ==========================================================
// CALCUL AUTOMATIQUE DU MONTANT EN CDF
// ==========================================================

function calculMontantCDF() {

    let montant =
        parseFloat(
            document.getElementById("montant").value
        ) || 0;

    let taux =
        parseFloat(
            document.getElementById("taux_change").value
        ) || 1;

    let devise =
        document.getElementById("devise").value;

    let tauxInput =
        document.getElementById("taux_change");

    let montantCDF =
        document.getElementById("montant_cdf");


    // Si la devise est USD

    if (devise === "USD") {

        tauxInput.readOnly = false;

        montantCDF.value =
            (montant * taux).toFixed(2);

    }

    // Si la devise est CDF

    else {

        tauxInput.value = 1;

        tauxInput.readOnly = true;

        montantCDF.value =
            montant.toFixed(2);

    }

}


// Quand le montant change

document
    .getElementById("montant")
    .addEventListener(
        "input",
        calculMontantCDF
    );


// Quand le taux change

document
    .getElementById("taux_change")
    .addEventListener(
        "input",
        calculMontantCDF
    );


// Quand la devise change

document
    .getElementById("devise")
    .addEventListener(
        "change",
        calculMontantCDF
    );


// Au chargement

window.addEventListener(
    "load",
    calculMontantCDF
);

</script>

</body>

</html>
