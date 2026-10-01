<?php
// ==========================================================
//                    BORDEREAU DE CAISSE
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


// ==========================================================
//                    VÉRIFICATION DE L'ID
// ==========================================================

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de l'opération introuvable.");
}

$id = intval($_GET['id']);


// ==========================================================
//                    RÉCUPÉRATION
// ==========================================================

$sql = "
    SELECT
        caisse.*,
        employe.nom,
        employe.postnom,
        employe.prenom

    FROM caisse

    LEFT JOIN employe
        ON caisse.id_employe = employe.id_employe

    WHERE caisse.id = $id
";

$resultat = mysqli_query($conn, $sql);

if (!$resultat) {
    die(
        "Erreur SQL : " .
        mysqli_error($conn)
    );
}


// Vérifier si l'opération existe

if (mysqli_num_rows($resultat) == 0) {
    die("Cette opération n'existe pas.");
}

$data = mysqli_fetch_assoc($resultat);


// ==========================================================
//                    EMPLOYÉ
// ==========================================================

$employe = trim(
    ($data['nom'].'') . ' ' .
    ($data['postnom'].'') . ' ' .
    ($data['prenom'].'')
);

if ($employe == '') {
    $employe = "Non attribué";
}


// ==========================================================
//                    FORMATAGE
// ==========================================================

$date = date(
    "d/m/Y",
    strtotime($data['date'])
);

$montant = number_format(
    $data['montant'],
    2,
    '.',
    ','
);

$montant_cdf = number_format(
    $data['montant_cdf'],
    2,
    '.',
    ','
);

$taux = number_format(
    $data['taux_change'],
    2,
    '.',
    ','
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

<title>
    Bordereau <?php echo  htmlspecialchars($data['reference_operation']); ?>
</title>


<style>

/* ==========================================================
                       PAGE
========================================================== */

body {

    font-family: Arial, sans-serif;

    background: #f2f2f2;

    margin: 0;

    padding: 20px;
}


/* ==========================================================
                       BORDEREAU
========================================================== */

.bordereau {

    width: 800px;

    max-width: 100%;

    margin: auto;

    background: white;

    padding: 35px;

    border: 1px solid #ccc;

    box-shadow:
        0 0 10px rgba(0,0,0,0.15);
}


/* ==========================================================
                       EN-TÊTE
========================================================== */

.entete {

    text-align: center;

    border-bottom: 2px solid #0066cc;

    padding-bottom: 15px;

    margin-bottom: 20px;
}

.entete h1 {

    margin: 0;

    color: #0066cc;

    font-size: 26px;
}

.entete h2 {

    margin: 8px 0;

    font-size: 20px;
}

.entete p {

    margin: 4px;

    color: #555;
}


/* ==========================================================
                     RÉFÉRENCE
========================================================== */

.reference {

    text-align: right;

    margin-bottom: 20px;

    font-weight: bold;
}

.reference span {

    color: #0066cc;
}


/* ==========================================================
                    TYPE OPÉRATION
========================================================== */

.type-operation {

    text-align: center;

    padding: 12px;

    margin-bottom: 20px;

    font-size: 20px;

    font-weight: bold;

    border-radius: 5px;
}

.entree {

    background: #d4edda;

    color: #155724;
}

.sortie {

    background: #f8d7da;

    color: #721c24;
}


/* ==========================================================
                       TABLE
========================================================== */

table {

    width: 100%;

    border-collapse: collapse;

    margin-top: 10px;
}

th {

    width: 35%;

    background: #f1f1f1;

    text-align: left;

    padding: 11px;

    border: 1px solid #ccc;
}

td {

    padding: 11px;

    border: 1px solid #ccc;
}


/* ==========================================================
                       MONTANT
========================================================== */

.montant {

    font-size: 18px;

    font-weight: bold;

    color: #0066cc;
}


/* ==========================================================
                       SIGNATURES
========================================================== */

.signatures {

    display: flex;

    justify-content: space-between;

    margin-top: 70px;

    text-align: center;
}

.signature {

    width: 40%;
}

.ligne {

    border-top: 1px solid #333;

    margin-top: 60px;

    padding-top: 8px;
}


/* ==========================================================
                         BOUTONS
========================================================== */

.boutons {

    text-align: center;

    margin-top: 30px;
}

.boutons button,
.boutons a {

    display: inline-block;

    padding: 11px 20px;

    margin: 5px;

    border: none;

    border-radius: 5px;

    text-decoration: none;

    color: white;

    cursor: pointer;

    font-size: 15px;
}

.imprimer {

    background: #0066cc;
}

.retour {

    background: #555;
}


/* ==========================================================
                     IMPRESSION
========================================================== */

@media print {

    body {

        background: white;

        padding: 0;
    }

    .bordereau {

        width: 100%;

        border: none;

        box-shadow: none;

        padding: 10px;
    }

    .boutons {

        display: none;
    }

}

</style>

</head>


<body>


<div class="bordereau">


<!-- ======================================================
                         EN-TÊTE
======================================================= -->

<div class="entete">

    <h1>
        GESTION DE CAISSE
    </h1>

    <h2>
        BORDEREAU D'OPÉRATION
    </h2>

    <p>
        Document officiel de caisse
    </p>

</div>


<!-- ======================================================
                       RÉFÉRENCE
======================================================= -->

<div class="reference">

    Référence :
    <span>
        <?php echo  htmlspecialchars(
            $data['reference_operation']
        ); ?>
    </span>

</div>


<!-- ======================================================
                    TYPE D'OPÉRATION
======================================================= -->

<?php if ($data['type_operation'] == "ENTREE") { ?>

    <div class="type-operation entree">

        ENTRÉE DE CAISSE

    </div>

<?php } else { ?>

    <div class="type-operation sortie">

        SORTIE DE CAISSE

    </div>

<?php } ?>


<!-- ======================================================
                    INFORMATIONS
======================================================= -->

<table>

<tr>

    <th>Date de l'opération</th>

    <td>
        <?php echo  htmlspecialchars($date); ?>
    </td>

</tr>


<tr>

    <th>Catégorie</th>

    <td>
        <?php echo  htmlspecialchars(
            $data['categorie']
        ); ?>
    </td>

</tr>


<tr>

    <th>Montant</th>

    <td class="montant">

        <?php echo  $montant; ?>

        <?php echo  htmlspecialchars(
            $data['devise']
        ); ?>

    </td>

</tr>


<tr>

    <th>Taux de change</th>

    <td>
        <?php echo  $taux; ?>
    </td>

</tr>


<tr>

    <th>Montant en CDF</th>

    <td class="montant">

        <?php echo  $montant_cdf; ?>

        CDF

    </td>

</tr>


<tr>

    <th>Mode de paiement</th>

    <td>
        <?php echo  htmlspecialchars(
            $data['mode_paiement']
        ); ?>
    </td>

</tr>


<tr>

    <th>Employé</th>

    <td>
        <?php echo  htmlspecialchars($employe); ?>
    </td>

</tr>


<tr>

    <th>Statut</th>

    <td>
        <?php echo  htmlspecialchars(
            $data['statut']
        ); ?>
    </td>

</tr>


<tr>

    <th>Description</th>

    <td>

        <?php echo  nl2br(
            htmlspecialchars(
                $data['description']
            )
        ); ?>

    </td>

</tr>

</table>


<!-- ======================================================
                       SIGNATURES
======================================================= -->

<div class="signatures">

    <div class="signature">

        <strong>
            Agent / Employé
        </strong>

        <div class="ligne">
            Signature
        </div>

    </div>


    <div class="signature">

        <strong>
            Responsable
        </strong>

        <div class="ligne">
            Signature et cachet
        </div>

    </div>

</div>


<!-- ======================================================
                         BOUTONS
======================================================= -->

<div class="boutons">

    <button
        class="imprimer"
        onclick="window.print();"
    >
        🖨️ Imprimer le bordereau
    </button>


    <a
        href="liste.php"
        class="retour"
    >
        ← Retour à la liste
    </a>

</div>


</div>


</body>

</html>
