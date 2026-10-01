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
   ENREGISTREMENT DU PAIEMENT
===================================================== */

if (isset($_POST['ajouter'])) {

    $date = $_POST['date'];
    $montant_peye = (float) $_POST['montant_peye'];
    $reste = (float) $_POST['reste'];
    $devise = $_POST['devise'];
    $id_dette = (int) $_POST['id_dette'];


    $sql = "INSERT INTO payement_dette
            (`date`, montant_peye, reste, devise, id_dette)
            VALUES (?, ?, ?, ?, ?)";


    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {

        die(
            "Erreur de préparation : "
            . mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $stmt,
        "sddsi",
        $date,
        $montant_peye,
        $reste,
        $devise,
        $id_dette
    );


    if (mysqli_stmt_execute($stmt)) {

        header("Location: liste.php");
        exit();

    } else {

        echo "Erreur lors de l'enregistrement : "
             . mysqli_stmt_error($stmt);
    }


    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="utf-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Ajouter un paiement
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

    color: #28a745;

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

    display: none;
}


.info-dette strong {

    color: #28a745;
}


button {

    width: 100%;

    background: #28a745;

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

    background: #218838;
}


a {

    display: block;

    text-align: center;

    margin-top: 15px;

    color: #007bff;

    text-decoration: none;
}

</style>

</head>


<body>


<div class="container">


<h2>
    Ajouter un paiement de dette
</h2>


<form
    method="POST"
    action=""
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
    value="<?php echo date('Y-m-d'); ?>"
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

$sql_dette = "
    SELECT
        id_dette,
        nature_dete,
       montant_dette ,
        devise
    FROM dette
    ORDER BY nature_dete ASC
";


$resultat_dette = mysqli_query(
    $conn,
    $sql_dette
);


if ($resultat_dette) {

    while (
        $dette =
        mysqli_fetch_assoc(
            $resultat_dette
        )
    ) {

?>

<option
    value="<?php echo $dette['id_dette']; ?>"
    data-montant="<?php echo $dette['montant_dette']; ?>"
    data-devise="<?php echo $dette['devise']; ?>"
>

<?php

echo htmlspecialchars(
    $dette['nature_dete']
);

?>

</option>

<?php

    }

} else {

    echo "
    <option value=''>
        Erreur de chargement des dettes
    </option>";
}

?>

</select>


<!-- ==============================
     INFORMATIONS DETTE
============================== -->

<div
    class="info-dette"
    id="infoDette"
>

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

<option value="">
    -- Sélectionner --
</option>

<option value="USD">
    USD
</option>

<option value="CDF">
    CDF
</option>

</select>


<!-- ==============================
     BOUTON
============================== -->

<button
    type="submit"
    name="ajouter"
>

    Enregistrer le paiement

</button>


<a href="liste.php">
    Voir la liste des paiements
</a>


</form>


</div>


<script>

/* =====================================================
   ELEMENTS
===================================================== */

const detteSelect =
    document.getElementById("id_dette");

const montantDette =
    document.getElementById("montantDette");

const deviseDette =
    document.getElementById("deviseDette");

const infoDette =
    document.getElementById("infoDette");

const montantPaye =
    document.getElementById("montant_peye");

const reste =
    document.getElementById("reste");

const devise =
    document.getElementById("devise");


/* =====================================================
   SELECTION DE LA DETTE
===================================================== */

detteSelect.addEventListener(
    "change",
    function () {

        const option =
            this.options[this.selectedIndex];


        if (!option.value) {

            montantDette.textContent = "0.00";

            deviseDette.textContent = "-";

            infoDette.style.display = "none";

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


        infoDette.style.display =
            "block";


        /*
         * Sélection automatique
         * de la devise de la dette
         */

        if (deviseDetteValue) {

            devise.value =
                deviseDetteValue;
        }


        calculerReste();
    }
);


/* =====================================================
   CALCUL AUTOMATIQUE DU RESTE
===================================================== */

function calculerReste() {

    const option =
        detteSelect.options[
            detteSelect.selectedIndex
        ];


    if (!option || !option.value) {

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


    /*
     * Empêcher un reste négatif
     */

    if (resultat < 0) {

        resultat = 0;
    }


    reste.value =
        resultat.toFixed(2);
}


/* =====================================================
   EVENEMENT MONTANT PAYE
===================================================== */

montantPaye.addEventListener(
    "input",
    calculerReste
);

</script>


</body>

</html>
