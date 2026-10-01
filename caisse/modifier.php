<?php

// ==========================================================
//                    MODIFIER UNE OPÉRATION
// ==========================================================

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect(
    $serveur,
    $utilisateur,
    $motdepasse,
    $base
);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

$message = "";


// ==========================================================
//                    VÉRIFIER L'ID
// ==========================================================

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de l'opération introuvable.");
}

$id = intval($_GET['id']);


// ==========================================================
//              ENREGISTRER LES MODIFICATIONS
// ==========================================================

if (isset($_POST['modifier'])) {

    $date_operation = $_POST['date'];
    $type_operation = $_POST['type_operation'];
    $categorie = trim($_POST['categorie']);
    $montant = floatval($_POST['montant']);
    $devise = $_POST['devise'];
    $taux_change = floatval($_POST['taux_change']);
    $mode_paiement = $_POST['mode_paiement'];
    $description = trim($_POST['description']);
    $id_employe = intval($_POST['id_employe']);
    $statut = $_POST['statut'];


    // ======================================================
    // CALCUL AUTOMATIQUE DU MONTANT CDF
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
    //              MISE À JOUR
    // ======================================================

    $sql = "
        UPDATE caisse SET

            date= ?,
            type_operation = ?,
            categorie = ?,
            montant = ?,
            devise = ?,
            taux_change = ?,
            montant_cdf = ?,
            mode_paiement = ?,
            description = ?,
            id_employe = ?,
            statut = ?,
            date_modification = NOW()

        WHERE id = ?
    ";


    $stmt = mysqli_prepare($conn, $sql);


    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "sssdsddssisi",
            $date_operation,
            $type_operation,
            $categorie,
            $montant,
            $devise,
            $taux_change,
            $montant_cdf,
            $mode_paiement,
            $description,
            $id_employe,
            $statut,
            $id
        );


        if (mysqli_stmt_execute($stmt)) {

            $message = "
                <div class='success'>
                    ✓ Opération modifiée avec succès.
                </div>
            ";

        } else {

            $message = "
                <div class='error'>
                    Erreur lors de la modification :
                    " .
                    htmlspecialchars(
                        mysqli_stmt_error($stmt)
                    )
                    . "
                </div>
            ";
        }


        mysqli_stmt_close($stmt);

    } else {

        $message = "
            <div class='error'>
                Erreur SQL :
                " .
                htmlspecialchars(
                    mysqli_error($conn)
                )
                . "
            </div>
        ";
    }
}


// ==========================================================
//             RÉCUPÉRER L'OPÉRATION
// ==========================================================

$sql_operation = "
    SELECT *
    FROM caisse
    WHERE id = $id
";

$resultat_operation = mysqli_query(
    $conn,
    $sql_operation
);


if (!$resultat_operation) {

    die(
        "Erreur SQL : " .
        mysqli_error($conn)
    );
}


if (mysqli_num_rows($resultat_operation) == 0) {

    die("Cette opération n'existe pas.");
}


$operation = mysqli_fetch_assoc(
    $resultat_operation
);

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Modifier une opération</title>


<style>

* {
    box-sizing: border-box;
}

body {

    font-family: Arial, sans-serif;

    background: #f2f2f2;

    margin: 0;

    padding: 20px;
}


.container {

    width: 700px;

    max-width: 100%;

    margin: 20px auto;

    background: white;

    padding: 25px;

    border-radius: 10px;

    box-shadow:
        0 0 10px #ccc;
}


h2 {

    text-align: center;

    color: #0066cc;

    margin-bottom: 25px;
}


label {

    display: block;

    font-weight: bold;

    margin-top: 12px;

    margin-bottom: 5px;
}


input,
select,
textarea {

    width: 100%;

    padding: 10px;

    border: 1px solid #ccc;

    border-radius: 5px;

    font-size: 15px;
}


input:focus,
select:focus,
textarea:focus {

    outline: none;

    border-color: #0066cc;
}


textarea {

    resize: vertical;
}


button {

    width: 100%;

    padding: 12px;

    margin-top: 20px;

    background: #0066cc;

    color: white;

    border: none;

    border-radius: 5px;

    font-size: 16px;

    font-weight: bold;

    cursor: pointer;
}


button:hover {

    background: #004999;
}


.success {

    background: #d4edda;

    color: #155724;

    padding: 12px;

    border-radius: 5px;

    margin-bottom: 15px;
}


.error {

    background: #f8d7da;

    color: #721c24;

    padding: 12px;

    border-radius: 5px;

    margin-bottom: 15px;
}


.reference {

    background: #f1f1f1;

    padding: 10px;

    border-radius: 5px;

    margin-bottom: 15px;

    font-weight: bold;
}


.retour {

    display: block;

    text-align: center;

    margin-top: 15px;

    padding: 10px;

    background: #555;

    color: white;

    text-decoration: none;

    border-radius: 5px;
}


.retour:hover {

    background: #333;
}

</style>

</head>


<body>


<div class="container">


<h2>
    Modifier l'opération de caisse
</h2>


<?php

echo $message;

?>


<div class="reference">

    Référence :
    <?= htmlspecialchars(
        $operation['reference_operation']
    ); ?>

</div>


<form method="POST">


<!-- ======================================================
                         DATE
======================================================= -->

<label>
    Date de l'opération
</label>

<input
    type="date"
    name="date_operation"
    value="<?= htmlspecialchars(
        $operation['date_operation']
    ); ?>"
    required
>


<!-- ======================================================
                         TYPE
======================================================= -->

<label>
    Type d'opération
</label>

<select
    name="type_operation"
    required
>

    <option
        value="ENTREE"
        <?= ($operation['type_operation'] == "ENTREE")
            ? "selected"
            : ""; ?>
    >
        ENTRÉE
    </option>


    <option
        value="SORTIE"
        <?= ($operation['type_operation'] == "SORTIE")
            ? "selected"
            : ""; ?>
    >
        SORTIE
    </option>

</select>


<!-- ======================================================
                       CATEGORIE
======================================================= -->

<label>
    Catégorie
</label>

<input
    type="text"
    name="categorie"
    value="<?= htmlspecialchars(
        $operation['categorie']
    ); ?>"
    placeholder="Ex : Vente, Achat, Salaire..."
    required
>


<!-- ======================================================
                         MONTANT
======================================================= -->

<label>
    Montant
</label>

<input
    type="number"
    step="0.01"
    min="0"
    id="montant"
    name="montant"
    value="<?= htmlspecialchars(
        $operation['montant']
    ); ?>"
    required
>


<!-- ======================================================
                         DEVISE
======================================================= -->

<label>
    Devise
</label>

<select
    name="devise"
    id="devise"
    required
>

    <option
        value="USD"
        <?= ($operation['devise'] == "USD")
            ? "selected"
            : ""; ?>
    >
        USD
    </option>


    <option
        value="CDF"
        <?= ($operation['devise'] == "CDF")
            ? "selected"
            : ""; ?>
    >
        CDF
    </option>

</select>


<!-- ======================================================
                    TAUX DE CHANGE
======================================================= -->

<label>
    Taux de change
</label>

<input
    type="number"
    step="0.01"
    min="0"
    id="taux_change"
    name="taux_change"
    value="<?= htmlspecialchars(
        $operation['taux_change']
    ); ?>"
    required
>


<!-- ======================================================
                    MONTANT CDF
======================================================= -->

<label>
    Montant en CDF
</label>

<input
    type="number"
    step="0.01"
    id="montant_cdf"
    value="<?= htmlspecialchars(
        $operation['montant_cdf']
    ); ?>"
    readonly
>


<!-- ======================================================
                   MODE DE PAIEMENT
======================================================= -->

<label>
    Mode de paiement
</label>

<select
    name="mode_paiement"
    required
>

    <option
        value="Espèces"
        <?= ($operation['mode_paiement'] == "Espèces")
            ? "selected"
            : ""; ?>
    >
        Espèces
    </option>


    <option
        value="Mobile Money"
        <?= ($operation['mode_paiement'] == "Mobile Money")
            ? "selected"
            : ""; ?>
    >
        Mobile Money
    </option>


    <option
        value="Banque"
        <?= ($operation['mode_paiement'] == "Banque")
            ? "selected"
            : ""; ?>
    >
        Banque
    </option>


    <option
        value="Carte bancaire"
        <?= ($operation['mode_paiement'] == "Carte bancaire")
            ? "selected"
            : ""; ?>
    >
        Carte bancaire
    </option>


    <option
        value="Chèque"
        <?= ($operation['mode_paiement'] == "Chèque")
            ? "selected"
            : ""; ?>
    >
        Chèque
    </option>

</select>


<!-- ======================================================
                       DESCRIPTION
======================================================= -->

<label>
    Description
</label>

<textarea
    name="description"
    rows="4"
    placeholder="Description de l'opération..."
><?= htmlspecialchars(
    $operation['description']
); ?></textarea>


<!-- ======================================================
                         EMPLOYE
======================================================= -->

<label>
    Employé
</label>

<select
    name="id_employe"
    required
>

    <option value="">
        -- Sélectionner un employé --
    </option>


<?php

$sql_employe = "
    SELECT id_employe, nom, postnom, prenom
    FROM employe
    ORDER BY nom ASC
";

$resultat_employe = mysqli_query(
    $conn,
    $sql_employe
);


if (!$resultat_employe) {

    echo "
        <option value=''>
            Erreur de chargement des employés
        </option>
    ";

} else {

    while (
        $employe = mysqli_fetch_assoc(
            $resultat_employe
        )
    ) {

        $nom_complet =
            $employe['nom'] . " " .
            $employe['postnom'] . " " .
            $employe['prenom'];

?>

    <option
        value="<?php echo $employe['id_employe']; ?>"
        <?php echo ($operation['id_employe'] == $employe['id_employe'])
            ? "selected"
            : ""; ?>
    >

        <?php echo htmlspecialchars(
            $nom_complet
        ); ?>

    </option>

<?php

    }

}

?>

</select>


<!-- ======================================================
                         STATUT
======================================================= -->

<label>
    Statut
</label>

<select
    name="statut"
    required
>

    <option
        value="EN ATTENTE"
        <?= ($operation['statut'] == "EN ATTENTE")
            ? "selected"
            : ""; ?>
    >
        EN ATTENTE
    </option>


    <option
        value="VALIDÉ"
        <?= ($operation['statut'] == "VALIDÉ")
            ? "selected"
            : ""; ?>
    >
        VALIDÉ
    </option>

</select>


<!-- ======================================================
                         BOUTON
======================================================= -->

<button
    type="submit"
    name="modifier"
>
    Enregistrer les modifications
</button>


</form>


<a
    href="liste.php"
    class="retour"
>
    ← Retour à la liste
</a>


</div>


<script>

// ==========================================================
//             CALCUL AUTOMATIQUE DU MONTANT CDF
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


    if (devise === "USD") {

        tauxInput.readOnly = false;

        montantCDF.value =
            (montant * taux).toFixed(2);

    } else {

        tauxInput.value = 1;

        tauxInput.readOnly = true;

        montantCDF.value =
            montant.toFixed(2);
    }
}


// Montant

document
    .getElementById("montant")
    .addEventListener(
        "input",
        calculMontantCDF
    );


// Taux

document
    .getElementById("taux_change")
    .addEventListener(
        "input",
        calculMontantCDF
    );


// Devise

document
    .getElementById("devise")
    .addEventListener(
        "change",
        calculMontantCDF
    );


// Chargement

window.addEventListener(
    "load",
    calculMontantCDF
);

</script>


</body>

</html>
