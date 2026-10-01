<?php

/* =========================================================
   RAPPORT.PHP
   Gestion de stock / Gestion d'entreprise
========================================================= */

/* ---------------------------------------------------------
   1. AFFICHAGE DES ERREURS
--------------------------------------------------------- */

error_reporting(E_ALL);
ini_set('display_errors', 1);


/* ---------------------------------------------------------
   2. CONNEXION À LA BASE
--------------------------------------------------------- */

include("connexion.php");

if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

mysqli_set_charset($conn, "utf8");


/* ---------------------------------------------------------
   3. FILTRE MOIS / ANNÉE
--------------------------------------------------------- */

$mois = isset($_GET['mois']) ? (int)$_GET['mois'] : (int)date('m');
$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : (int)date('Y');

if ($mois < 1 || $mois > 12) {
    $mois = (int)date('m');
}

if ($annee < 2020 || $annee > date('Y')) {
    $annee = (int)date('Y');
}

$date_debut = sprintf(
    "%04d-%02d-01",
    $annee,
    $mois
);

$date_fin = date(
    "Y-m-t",
    strtotime($date_debut)
);


/* ---------------------------------------------------------
   4. NOM DES MOIS
--------------------------------------------------------- */

$mois_fr = array(
    1 => "Janvier",
    2 => "Février",
    3 => "Mars",
    4 => "Avril",
    5 => "Mai",
    6 => "Juin",
    7 => "Juillet",
    8 => "Août",
    9 => "Septembre",
    10 => "Octobre",
    11 => "Novembre",
    12 => "Décembre"
);

$nom_mois = $mois_fr[$mois] . " " . $annee;


/* ---------------------------------------------------------
   5. FONCTION POUR SAVOIR SI UNE COLONNE EXISTE
--------------------------------------------------------- */

function colonneExiste($conn, $table, $colonne)
{
    $table = mysqli_real_escape_string($conn, $table);
    $colonne = mysqli_real_escape_string($conn, $colonne);

    $sql = "
        SELECT COUNT(*) AS total
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = '$table'
        AND COLUMN_NAME = '$colonne'
    ";

    $resultat = mysqli_query($conn, $sql);

    if (!$resultat) {
        return false;
    }

    $ligne = mysqli_fetch_assoc($resultat);

    return ((int)$ligne['total'] > 0);
}


/* ---------------------------------------------------------
   6. TROUVER AUTOMATIQUEMENT LA COLONNE DE DATE
--------------------------------------------------------- */

function trouverColonneDate($conn, $table, $colonnes)
{
    foreach ($colonnes as $colonne) {

        if (colonneExiste($conn, $table, $colonne)) {
            return $colonne;
        }
    }

    return null;
}


/* ---------------------------------------------------------
   7. COMPTER LES ENREGISTREMENTS
--------------------------------------------------------- */

function compterTable($conn, $table, $condition = "")
{
    $sql = "SELECT COUNT(*) AS total FROM `$table` $condition";

    $resultat = mysqli_query($conn, $sql);

    if (!$resultat) {
        return 0;
    }

    $ligne = mysqli_fetch_assoc($resultat);

    return (int)$ligne['total'];
}


/* ---------------------------------------------------------
   8. CALCULER UNE SOMME
--------------------------------------------------------- */

function calculerSomme($conn, $table, $colonne, $condition = "")
{
    if (!colonneExiste($conn, $table, $colonne)) {
        return 0;
    }

    $sql = "
        SELECT COALESCE(SUM(`$colonne`), 0) AS total
        FROM `$table`
        $condition
    ";

    $resultat = mysqli_query($conn, $sql);

    if (!$resultat) {
        return 0;
    }

    $ligne = mysqli_fetch_assoc($resultat);

    return (float)$ligne['total'];
}


/* ---------------------------------------------------------
   9. CRÉER UNE CONDITION DE DATE
--------------------------------------------------------- */

function conditionDate($conn, $table, $date_debut, $date_fin)
{
    $colonne = trouverColonneDate(
        $conn,
        $table,
        array(
            "date",
            "date_operation",
            "date_depense",
            "date_vente",
            "date_facture",
            "date_creation",
            "created_at"
        )
    );

    if ($colonne === null) {
        return "";
    }

    return "
        WHERE `$colonne`
        BETWEEN '$date_debut'
        AND '$date_fin'
    ";
}


/* =========================================================
   10. CONDITIONS DE DATE
========================================================= */

$cond_stock = conditionDate(
    $conn,
    "stock",
    $date_debut,
    $date_fin
);

$cond_appro = conditionDate(
    $conn,
    "approvisionnement",
    $date_debut,
    $date_fin
);

$cond_vente = conditionDate(
    $conn,
    "vente",
    $date_debut,
    $date_fin
);

$cond_facture = conditionDate(
    $conn,
    "facture",
    $date_debut,
    $date_fin
);

$cond_depenses = conditionDate(
    $conn,
    "depenses",
    $date_debut,
    $date_fin
);

$cond_caisse = conditionDate(
    $conn,
    "caisse",
    $date_debut,
    $date_fin
);

$cond_dette = conditionDate(
    $conn,
    "dette",
    $date_debut,
    $date_fin
);

$cond_paiement = conditionDate(
    $conn,
    "payement_dette",
    $date_debut,
    $date_fin
);


/* =========================================================
   11. COMPTAGES
========================================================= */

$nbEmployes = compterTable(
    $conn,
    "employe"
);

$nbClients = compterTable(
    $conn,
    "client"
);

$nbFournisseurs = compterTable(
    $conn,
    "fournisseur"
);

$nbArticles = compterTable(
    $conn,
    "article"
);

$nbDevises = compterTable(
    $conn,
    "devise"
);

$nbStock = compterTable(
    $conn,
    "stock",
    $cond_stock
);

$nbAppros = compterTable(
    $conn,
    "approvisionnement",
    $cond_appro
);

$nbVentes = compterTable(
    $conn,
    "vente",
    $cond_vente
);

$nbFactures = compterTable(
    $conn,
    "facture",
    $cond_facture
);

$nbDepenses = compterTable(
    $conn,
    "depenses",
    $cond_depenses
);

$nbCaisses = compterTable(
    $conn,
    "caisse",
    $cond_caisse
);

$nbDettes = compterTable(
    $conn,
    "dette",
    $cond_dette
);

$nbPaiements = compterTable(
    $conn,
    "payement_dette",
    $cond_paiement
);


/* =========================================================
   12. SOMMES FINANCIÈRES
========================================================= */

$totalVentes = calculerSomme(
    $conn,
    "vente",
    "montant",
    $cond_vente
);

$totalDepenses = calculerSomme(
    $conn,
    "depenses",
    "montant",
    $cond_depenses
);

$totalCaisse = calculerSomme(
    $conn,
    "caisse",
    "montant",
    $cond_caisse
);

$totalDettes = calculerSomme(
    $conn,
    "dette",
    "montant_dette",
    $cond_dette
);

$totalPaiements = calculerSomme(
    $conn,
    "payement_dette",
    "montant_peye",
    $cond_paiement
);


/* =========================================================
   13. BÉNÉFICE
========================================================= */

$benefice = $totalVentes - $totalDepenses;


/* =========================================================
   14. FORMAT MONNAIE
========================================================= */

function monnaie($montant)
{
    return number_format(
        (float)$montant,
        0,
        ',',
        ' '
    ) . " FC";
}


/* =========================================================
   15. HEADER ET SIDEBAR
========================================================= */

include("header.php");
include("sidebar.php");

?>


<!-- =======================================================
     STYLE DU RAPPORT
======================================================= -->

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f4f6f9;
}


/* -------------------------------------------------------
   CONTENU PRINCIPAL
------------------------------------------------------- */

.rapport-page {

    margin-left: 250px;

    padding: 30px;

    min-height: 100vh;

    background: #f4f6f9;

}


/* -------------------------------------------------------
   TITRE
------------------------------------------------------- */

.rapport-titre {

    background: white;

    padding: 25px;

    border-radius: 12px;

    margin-bottom: 25px;

    box-shadow:
        0 2px 10px rgba(0,0,0,0.08);

}

.rapport-titre h1 {

    margin: 0;

    font-size: 26px;

    color: #222;

}

.rapport-titre p {

    margin: 8px 0 0;

    color: #777;

}


/* -------------------------------------------------------
   FILTRE
------------------------------------------------------- */

.filtre {

    background: white;

    padding: 20px;

    border-radius: 12px;

    margin-bottom: 25px;

    box-shadow:
        0 2px 10px rgba(0,0,0,0.08);

}

.filtre form {

    display: flex;

    gap: 12px;

    align-items: center;

    flex-wrap: wrap;

}

.filtre label {

    font-weight: bold;

}

.filtre select {

    padding: 10px;

    border: 1px solid #ddd;

    border-radius: 6px;

    min-width: 130px;

}

.btn-filtrer {

    padding: 10px 18px;

    background: #0d6efd;

    color: white;

    border: none;

    border-radius: 6px;

    cursor: pointer;

}

.btn-filtrer:hover {

    background: #084298;

}

.btn-imprimer {

    padding: 10px 18px;

    background: #6c757d;

    color: white;

    border: none;

    border-radius: 6px;

    cursor: pointer;

}


/* -------------------------------------------------------
   TITRES DE SECTION
------------------------------------------------------- */

.section-titre {

    margin-top: 30px;

    margin-bottom: 18px;

    color: #333;

    font-size: 20px;

    font-weight: bold;

}


/* -------------------------------------------------------
   GRILLE
------------------------------------------------------- */

.cartes {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(200px, 1fr));

    gap: 20px;

}


/* -------------------------------------------------------
   CARTE
------------------------------------------------------- */

.carte {

    background: white;

    padding: 22px;

    border-radius: 12px;

    min-height: 130px;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.08);

    border-left: 5px solid #0d6efd;

}

.carte h3 {

    margin: 0;

    font-size: 14px;

    color: #777;

    text-transform: uppercase;

}

.carte .valeur {

    margin-top: 10px;

    font-size: 25px;

    font-weight: bold;

    color: #222;

}


/* -------------------------------------------------------
   COULEURS
------------------------------------------------------- */

.bleu {
    border-left-color: #0d6efd;
}

.vert {
    border-left-color: #198754;
}

.rouge {
    border-left-color: #dc3545;
}

.orange {
    border-left-color: #fd7e14;
}

.violet {
    border-left-color: #6f42c1;
}

.turquoise {
    border-left-color: #20c997;
}

.rose {
    border-left-color: #d63384;
}

.indigo {
    border-left-color: #6610f2;
}


/* -------------------------------------------------------
   GRAPHIQUE
------------------------------------------------------- */

.graphique-box {

    background: white;

    padding: 25px;

    border-radius: 12px;

    margin-top: 25px;

    box-shadow:
        0 3px 10px rgba(0,0,0,0.08);

}

.graphique-box h3 {

    margin-top: 0;

    color: #333;

}

.graphique-container {

    width: 100%;

    max-width: 650px;

    height: 350px;

    margin: auto;

}


/* -------------------------------------------------------
   RESPONSIVE
------------------------------------------------------- */

@media (max-width: 1100px) {

    .cartes {

        grid-template-columns:
            repeat(2, minmax(200px, 1fr));

    }

}

@media (max-width: 768px) {

    .rapport-page {

        margin-left: 0;

        padding: 15px;

    }

    .cartes {

        grid-template-columns: 1fr;

    }

}


/* -------------------------------------------------------
   IMPRESSION
------------------------------------------------------- */

@media print {

    .filtre {

        display: none;

    }

    .rapport-page {

        margin-left: 0;

        padding: 10px;

    }

    .carte {

        box-shadow: none;

        border: 1px solid #ddd;

    }

}

</style>


<!-- =======================================================
     PAGE RAPPORT
======================================================= -->

<div class="rapport-page">


    <!-- ===================================================
         TITRE
    =================================================== -->

    <div class="rapport-titre">

        <h1>

            📊 Rapport mensuel

        </h1>

        <p>

            Période :

            <strong>

                <?php

                echo date(
                    "d/m/Y",
                    strtotime($date_debut)
                );

                ?>

            </strong>

            au

            <strong>

                <?php

                echo date(
                    "d/m/Y",
                    strtotime($date_fin)
                );

                ?>

            </strong>

        </p>

    </div>


    <!-- ===================================================
         FILTRE
    =================================================== -->

    <div class="filtre">

        <form method="GET">

            <label>

                Mois :

            </label>


            <select name="mois">

                <?php

                foreach ($mois_fr as $numero => $nom) {

                    $selected =
                        ($numero == $mois)
                        ? "selected"
                        : "";

                    echo "
                        <option
                            value='$numero'
                            $selected>
                            $nom
                        </option>
                    ";
                }

                ?>

            </select>


            <label>

                Année :

            </label>


            <select name="annee">

                <?php

                for (
                    $a = 2023;
                    $a <= date('Y');
                    $a++
                ) {

                    $selected =
                        ($a == $annee)
                        ? "selected"
                        : "";

                    echo "
                        <option
                            value='$a'
                            $selected>
                            $a
                        </option>
                    ";
                }

                ?>

            </select>


            <button
                type="submit"
                class="btn-filtrer">

                🔎 Filtrer

            </button>


            <button
                type="button"
                onclick="window.print()"
                class="btn-imprimer">

                🖨 Imprimer

            </button>

        </form>

    </div>


    <!-- ===================================================
         FINANCES
    =================================================== -->

    <div class="section-titre">

        💰 Situation financière

    </div>


    <div class="cartes">


        <!-- VENTES -->

        <div class="carte vert">

            <h3>
                Chiffre d'affaires
            </h3>

            <div class="valeur">

                <?php

                echo monnaie($totalVentes);

                ?>

            </div>

        </div>


        <!-- DEPENSES -->

        <div class="carte rouge">

            <h3>
                Dépenses
            </h3>

            <div class="valeur">

                <?php

                echo monnaie($totalDepenses);

                ?>

            </div>

        </div>


        <!-- CAISSE -->

        <div class="carte bleu">

            <h3>
                Caisse
            </h3>

            <div class="valeur">

                <?php

                echo monnaie($totalCaisse);

                ?>

            </div>

        </div>


        <!-- DETTES -->

        <div class="carte orange">

            <h3>
                Dettes
            </h3>

            <div class="valeur">

                <?php

                echo monnaie($totalDettes);

                ?>

            </div>

        </div>


        <!-- PAIEMENTS -->

        <div class="carte indigo">

            <h3>
                Paiements de dettes
            </h3>

            <div class="valeur">

                <?php

                echo monnaie($totalPaiements);

                ?>

            </div>

        </div>


        <!-- BENEFICE -->

        <div class="carte turquoise">

            <h3>
                Résultat / bénéfice
            </h3>

            <div class="valeur">

                <?php

                echo monnaie($benefice);

                ?>

            </div>

        </div>

    </div>


    <!-- ===================================================
         ACTIVITÉS
    =================================================== -->

    <div class="section-titre">

        📋 Activités et opérations

    </div>


    <div class="cartes">


        <div class="carte bleu">

            <h3>
                Employés
            </h3>

            <div class="valeur">

                <?php echo $nbEmployes; ?>

            </div>

        </div>


        <div class="carte vert">

            <h3>
                Clients
            </h3>

            <div class="valeur">

                <?php echo $nbClients; ?>

            </div>

        </div>


        <div class="carte violet">

            <h3>
                Fournisseurs
            </h3>

            <div class="valeur">

                <?php echo $nbFournisseurs; ?>

            </div>

        </div>


        <div class="carte turquoise">

            <h3>
                Articles
            </h3>

            <div class="valeur">

                <?php echo $nbArticles; ?>

            </div>

        </div>


        <div class="carte orange">

            <h3>
                Stock
            </h3>

            <div class="valeur">

                <?php echo $nbStock; ?>

            </div>

        </div>


        <div class="carte rouge">

            <h3>
                Approvisionnements
            </h3>

            <div class="valeur">

                <?php echo $nbAppros; ?>

            </div>

        </div>


        <div class="carte rose">

            <h3>
                Ventes
            </h3>

            <div class="valeur">

                <?php echo $nbVentes; ?>

            </div>

        </div>


        <div class="carte violet">

            <h3>
                Factures
            </h3>

            <div class="valeur">

                <?php echo $nbFactures; ?>

            </div>

        </div>


        <div class="carte indigo">

            <h3>
                Paiements
            </h3>

            <div class="valeur">

                <?php echo $nbPaiements; ?>

            </div>

        </div>


        <div class="carte turquoise">

            <h3>
                Devises
            </h3>

            <div class="valeur">

                <?php echo $nbDevises; ?>

            </div>

        </div>


        <div class="carte bleu">

            <h3>
                Opérations caisse
            </h3>

            <div class="valeur">

                <?php echo $nbCaisses; ?>

            </div>

        </div>


        <div class="carte orange">

            <h3>
                Dettes
            </h3>

            <div class="valeur">

                <?php echo $nbDettes; ?>

            </div>

        </div>

    </div>


   <!-- =====================================================
     GRAPHIQUE
===================================================== -->

<div class="graphique-box">

<h3>
    📊 Analyse financière -
    <?php echo $nom_mois; ?>
</h3>

<div style="
    position: relative;
    width: 100%;
    height: 400px;
">
    <canvas id="graphiqueRapport"></canvas>
</div>

</div>


<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

const canvas = document.getElementById("graphiqueRapport");

if (!canvas) {
    console.log("Canvas introuvable");
    return;
}

new Chart(canvas, {

    type: "bar",

    data: {

        labels: [
            "Ventes",
            "Dépenses",
            "Dettes",
            "Paiements"
        ],

        datasets: [{
            label: "Montant (FC)",

            data: [
                <?php echo (float)$totalVentes; ?>,
                <?php echo (float)$totalDepenses; ?>,
                <?php echo (float)$totalDettes; ?>,
                <?php echo (float)$totalPaiements; ?>
            ]
        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        scales: {

            y: {

                beginAtZero: true

            }

        },

        plugins: {

            legend: {

                display: true

            }

        }

    }

});

});

</script>


<?php

/* =========================================================
   FOOTER
========================================================= */

include("footer.php");

?>

