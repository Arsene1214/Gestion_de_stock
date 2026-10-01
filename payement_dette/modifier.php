<?php

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


/* =====================================================
   VERIFICATION DE L'ID
===================================================== */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du paiement introuvable.");
}

$id = (int) $_GET['id'];


/* =====================================================
   MODIFICATION
===================================================== */

if (isset($_POST['modifier'])) {

    $date = $_POST['date'];

    $montant_paye =
        (float) $_POST['montant_peye'];

    $reste =
        (float) $_POST['reste'];

    $devise =
        $_POST['devise'];

    $id_dette =
        (int) $_POST['id_dette'];


    $sql = "
        UPDATE payement_dette
        SET
            `date` = ?,
            montant_peye = ?,
            reste = ?,
            devise = ?,
            id_dette = ?
        WHERE id_dette_pey = ?
    ";


    $stmt = mysqli_prepare(
        $conn,
        $sql
    );


    if (!$stmt) {

        die(
            "Erreur de préparation : "
            . mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $stmt,
        "sdd sii",
        $date,
        $montant_paye,
        $reste,
        $devise,
        $id_dette,
        $id
    );


    /*
     * Correction de l'espace éventuel
     * dans le type de bind_param
     */

    mysqli_stmt_close($stmt);


    $stmt = mysqli_prepare(
        $conn,
        $sql
    );


    mysqli_stmt_bind_param(
        $stmt,
        "sddsii",
        $date,
        $montant_paye,
        $reste,
        $devise,
        $id_dette,
        $id
    );


    if (mysqli_stmt_execute($stmt)) {

        header("Location: liste.php");
        exit();

    } else {

        echo "Erreur lors de la modification : "
             . mysqli_stmt_error($stmt);
    }


    mysqli_stmt_close($stmt);
}


/* =====================================================
   RECUPERATION DU PAIEMENT
===================================================== */

$sql_paiement = "
    SELECT *
    FROM payement_dette
    WHERE id_dette_pey = ?
";


$stmt_paiement = mysqli_prepare(
    $conn,
    $sql_paiement
);


mysqli_stmt_bind_param(
    $stmt_paiement,
    "i",
    $id
);


mysqli_stmt_execute(
    $stmt_paiement
);


$resultat_paiement =
    mysqli_stmt_get_result(
        $stmt_paiement
    );


if (
    mysqli_num_rows(
        $resultat_paiement
    ) == 0
) {

    die("Paiement introuvable.");
}


$paiement =
    mysqli_fetch_assoc(
        $resultat_paiement
    );


mysqli_stmt_close(
    $stmt_paiement
);


/* =====================================================
   RECUPERATION DES DETTES
===================================================== */

$sql_dette = "
    SELECT
        id_dette,
        nature_dete,
        montant_dette,
        devise
    FROM dette
    ORDER BY nature_dete ASC
";


$resultat_dette =
    mysqli_query(
        $conn,
        $sql_dette
    );


if (!$resultat_dette) {

    die(
        "Erreur de chargement des dettes : "
        . mysqli_error($conn)
    );
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Modifier un paiement
</title>


<style>

body {

    font-family: Arial, sans-serif;

    background: #f4f4f4;

    margin: 0;

    padding: 30px;
}


.container {

    width: 80%;

    max-width: 650px;

    margin: auto;

    background: white;

    padding: 25px;

    border-radius: 10px;

    box-shadow:
        0 0 10px
        rgba(0,0,0,0.1);
}


h2 {

    text-align: center;

    color: #007bff;

    margin-bottom: 25px;
}


label {

    display: block;

    font-weight: bold;

    margin-top: 15px;

    margin-bottom: 7px;
}


input,
select {

    width: 100%;

    padding: 11px;

    border: 1px solid #ccc;

    border-radius: 5px;

    box-sizing: border-box;

    font-size: 15px;
}


input[readonly] {

    background: #eeeeee;

    font-weight: bold;
}


.info-dette {

    background: #f1f8f3;

    border-left: 4px solid #28a745;

    padding: 12px;

    margin-top: 15px;

    margin-bottom: 15px;
}


.info-dette strong {

    color: #28a745;
}


button {

    width: 100%;

    background: #007bff;

    color: white;

    border: none;

    padding: 12px;

    cursor: pointer;

    border-radius: 5px;

    font-size: 16px;

    font-weight: bold;

    margin-top: 20px;
}


button:hover {

    background: #0056b3;
}


.retour {

    display: block;

    text-align: center;

    margin-top: 15px;

    color: #333;

    text-decoration: none;
}

</style>

</head>


<body>


<div class="container">


<h2>
    Modifier un paiement de dette
</h2>


<form
    method="POST"
    action="modifier.php?id=<?php echo $id; ?>"
>


<!-- ==============================
     DATE
============================== -->

<label>
    Date de paiement
</label>

<input
    type="date"
    name="date"
    value="<?php
        echo htmlspecialchars(
            $paiement['date']
        );
    ?>"
    required
>


<!-- ==============================
     DETTE
============================== -->

<label>
    Dette
</label>


<select
    name="id_dette"
    id="id_dette"
    required
>

<option value="">
    -- Sélectionner une dette --
</option>


<?php

while (
    $dette =
    mysqli_fetch_assoc(
        $resultat_dette
    )
) {

    $selected = "";

    if (
        $dette['id_dette']
        == $paiement['id_dette']
    ) {

        $selected = "selected";
    }

?>

<option

    value="<?php
        echo $dette['id_dette'];
    ?>"

    data-montant="<?php
        echo $dette['montant_dette'];
    ?>"

    data-devise="<?php
        echo $dette['devise'];
    ?>"

    <?php echo $selected; ?>

>

<?php

echo htmlspecialchars(
    $dette['nature_dete']
);

?>

</option>

<?php

}

?>

</select>


<!-- ==============================
     INFORMATIONS DETTE
============================== -->

<div class="info-dette">

    Montant de la dette :

    <strong>

        <span id="montantDette">
            0.00
        </span>

        <span id="deviseDette">
            -
        </span>

    </strong>

</div>


<!-- ==============================
     MONTANT PAYE
============================== -->

<label>
    Montant payé
</label>

<input
    type="number"
    step="0.01"
    min="0"
    name="montant_peye"
    id="montant_peye"

    value="<?php
        echo htmlspecialchars(
            $paiement['montant_peye']
        );
    ?>"

    required
>


<!-- ==============================
     RESTE
============================== -->

<label>
    Reste
</label>

<input
    type="number"
    step="0.01"
    min="0"
    name="reste"
    id="reste"

    value="<?php
        echo htmlspecialchars(
            $paiement['reste']
        );
    ?>"

    readonly
>


<!-- ==============================
     DEVISE
============================== -->

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
    <?php

    if (
        $paiement['devise']
        == "USD"
    ) {

        echo "selected";
    }

    ?>
>

    USD

</option>


<option
    value="CDF"
    <?php

    if (
        $paiement['devise']
        == "CDF"
    ) {

        echo "selected";
    }

    ?>
>

    CDF

</option>

</select>


<!-- ==============================
     BOUTON
============================== -->

<button
    type="submit"
    name="modifier"
>

    Enregistrer les modifications

</button>


<a
    href="liste.php"
    class="retour"
>

    ← Retour à la liste

</a>


</form>


</div>


<script>

/* =====================================================
   ELEMENTS
===================================================== */

const detteSelect =
    document.getElementById(
        "id_dette"
    );

const montantDette =
    document.getElementById(
        "montantDette"
    );

const deviseDette =
    document.getElementById(
        "deviseDette"
    );

const montantPaye =
    document.getElementById(
        "montant_peye"
    );

const reste =
    document.getElementById(
        "reste"
    );

const devise =
    document.getElementById(
        "devise"
    );


/* =====================================================
   AFFICHER LES INFORMATIONS DE LA DETTE
===================================================== */

function afficherDette() {

    const option =
        detteSelect.options[
            detteSelect.selectedIndex
        ];


    if (
        !option ||
        !option.value
    ) {

        montantDette.textContent =
            "0.00";

        deviseDette.textContent =
            "-";

        reste.value = "";

        return;
    }


    const montant =
        parseFloat(
            option.dataset.montant
        ) || 0;


    const deviseDetteValue =
        option.dataset.devise || "";


    montantDette.textContent =
        montant.toFixed(2);


    deviseDette.textContent =
        deviseDetteValue;


    /*
     * La devise de la dette
     * est sélectionnée automatiquement.
     */

    if (deviseDetteValue) {

        devise.value =
            deviseDetteValue;
    }


    calculerReste();
}


/* =====================================================
   CALCUL DU RESTE
===================================================== */

function calculerReste() {

    const option =
        detteSelect.options[
            detteSelect.selectedIndex
        ];


    if (
        !option ||
        !option.value
    ) {

        reste.value = "";

        return;
    }


    const montant =
        parseFloat(
            option.dataset.montant
        ) || 0;


    const paye =
        parseFloat(
            montantPaye.value
        ) || 0;


    let resultat =
        montant - paye;


    if (resultat < 0) {

        resultat = 0;
    }


    reste.value =
        resultat.toFixed(2);
}


/* =====================================================
   EVENEMENTS
===================================================== */

detteSelect.addEventListener(
    "change",
    afficherDette
);


montantPaye.addEventListener(
    "input",
    calculerReste
);


/* =====================================================
   CHARGEMENT INITIAL
===================================================== */

afficherDette();

</script>


</body>

</html>
