<?php
/* ==========================================================
   CONNEXION À LA BASE DE DONNÉES
   ========================================================== */

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
    die(
        "Erreur de connexion : "
        . mysqli_connect_error()
    );
}

mysqli_set_charset($conn, "utf8");


/* ==========================================================
   RECHERCHE
   ========================================================== */

$recherche = "";

if (isset($_GET['recherche'])) {

    $recherche = mysqli_real_escape_string(
        $conn,
        $_GET['recherche']
    );
}


/* ==========================================================
   REQUÊTE DES ARTICLES
   ========================================================== */

$sql = "
    SELECT
        article.id_article,
        article.date,
        article.nom_article,
        article.nombre_article,
        article.prix_article,

        employe.nom,
        employe.postnom,
        employe.prenom,

        stock.nom_produit,

        devise.nom_devise

    FROM article

    LEFT JOIN employe
        ON article.id_employe = employe.id_employe

    LEFT JOIN stock
        ON article.id_stock = stock.id_stock

    LEFT JOIN devise
        ON article.id_devise = devise.id_devise
";


/* ==========================================================
   AJOUT DE LA RECHERCHE
   ========================================================== */

if (!empty($recherche)) {

    $sql .= "
        WHERE
            article.nom_article LIKE '%$recherche%'
            OR stock.nom_produit LIKE '%$recherche%'
            OR devise.nom_devise LIKE '%$recherche%'
            OR employe.nom LIKE '%$recherche%'
            OR employe.postnom LIKE '%$recherche%'
            OR employe.prenom LIKE '%$recherche%'
    ";
}


/* ==========================================================
   TRI
   ========================================================== */

$sql .= "
    ORDER BY article.id_article DESC
";


$resultat = mysqli_query(
    $conn,
    $sql
);

if (!$resultat) {

    die(
        "Erreur SQL : "
        . mysqli_error($conn)
    );
}


/* ==========================================================
   TOTAL
   ========================================================== */

$sql_total = "
    SELECT
        COUNT(*) AS total
    FROM article
";

$resultat_total = mysqli_query(
    $conn,
    $sql_total
);

$total = 0;

if ($resultat_total) {

    $ligne_total =
        mysqli_fetch_assoc(
            $resultat_total
        );

    $total =
        $ligne_total['total'];
}

?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Liste des articles</title>


<style>

/* ==========================================================
   STYLE GÉNÉRAL
   ========================================================== */

body {

    font-family: Arial, sans-serif;

    background: #f4f6f9;

    margin: 0;

    padding: 20px;
}


/* ==========================================================
   CONTENEUR
   ========================================================== */

.container {

    max-width: 1400px;

    margin: auto;

    background: white;

    padding: 20px;

    border-radius: 10px;

    box-shadow:
        0 2px 10px
        rgba(0,0,0,0.1);

}


/* ==========================================================
   TITRE
   ========================================================== */

h2 {

    text-align: center;

    color: #333;

    margin-bottom: 25px;

}


/* ==========================================================
   BARRE SUPÉRIEURE
   ========================================================== */

.top {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;

    gap: 10px;

    flex-wrap: wrap;

}


/* ==========================================================
   RECHERCHE
   ========================================================== */

.recherche {

    display: flex;

    gap: 5px;

}


.recherche input {

    padding: 10px;

    width: 250px;

    border:
        1px solid #ccc;

    border-radius: 5px;

}


.btn-recherche {

    padding: 10px 15px;

    border: none;

    border-radius: 5px;

    background: #007bff;

    color: white;

    cursor: pointer;

}


.btn-recherche:hover {

    background: #0056b3;

}


/* ==========================================================
   BOUTON AJOUT
   ========================================================== */

.btn-ajouter {

    padding: 10px 15px;

    background: #28a745;

    color: white;

    text-decoration: none;

    border-radius: 5px;

}


.btn-ajouter:hover {

    background: #218838;

}


/* ==========================================================
   TOTAL
   ========================================================== */

.total {

    background: #e9f5ff;

    padding: 12px;

    margin-bottom: 20px;

    border-radius: 5px;

    font-weight: bold;

    color: #0056b3;

}


/* ==========================================================
   TABLEAU
   ========================================================== */

.table-container {

    overflow-x: auto;

}


table {

    width: 100%;

    border-collapse: collapse;

    min-width: 1100px;

}


th,
td {

    border:
        1px solid #ddd;

    padding: 10px;

    text-align: center;

}


th {

    background: #007bff;

    color: white;

}


tr:nth-child(even) {

    background: #f8f8f8;

}


tr:hover {

    background: #eef6ff;

}


/* ==========================================================
   BOUTONS ACTION
   ========================================================== */

.btn-modifier {

    background: #ffc107;

    color: #000;

    padding: 6px 10px;

    text-decoration: none;

    border-radius: 4px;

    display: inline-block;

    margin: 2px;

}


.btn-modifier:hover {

    background: #e0a800;

}


.btn-supprimer {

    background: #dc3545;

    color: white;

    padding: 6px 10px;

    text-decoration: none;

    border-radius: 4px;

    display: inline-block;

    margin: 2px;

}


.btn-supprimer:hover {

    background: #bd2130;

}


/* ==========================================================
   AUCUN ARTICLE
   ========================================================== */

.aucun {

    text-align: center;

    padding: 25px;

    color: #777;

}


/* ==========================================================
   MOBILE
   ========================================================== */

@media (max-width: 600px) {

    body {

        padding: 10px;

    }

    .container {

        padding: 10px;

    }

    .recherche {

        width: 100%;

    }

    .recherche input {

        width: 100%;

    }

}

</style>

</head>


<body>


<div class="container">


<h2>
    Liste des articles
</h2>


<!-- ======================================================
     BARRE SUPÉRIEURE
     ====================================================== -->

<div class="top">


<form
    action="liste.php"
    method="GET"
    class="recherche"
>

<input
    type="text"
    name="recherche"
    placeholder="Rechercher un article..."
    value="<?php
        echo htmlspecialchars($recherche);
    ?>"
>

<button
    type="submit"
    class="btn-recherche"
>
    Rechercher
</button>

</form>


<a
    href="ajouter.php"
    class="btn-ajouter"
>
    + Ajouter un article
</a>


</div>


<!-- ======================================================
     TOTAL
     ====================================================== -->

<div class="total">

    Nombre total d'articles :
    <?php echo $total; ?>

</div>


<!-- ======================================================
     TABLEAU
     ====================================================== -->

<div class="table-container">


<table>


<thead>

<tr>

    <th>N°</th>

    <th>Date</th>

    <th>Article</th>

    <th>Nombre</th>

    <th>Prix</th>

    <th>Devise</th>

    <th>Stock</th>

    <th>Employé</th>

    <th>Actions</th>

</tr>

</thead>


<tbody>


<?php

$numero = 1;

if (mysqli_num_rows($resultat) > 0) {

    while (
        $article =
        mysqli_fetch_assoc($resultat)
    ) {

        /* ----------------------------------------------
           Nom complet de l'employé
           ---------------------------------------------- */

        $employe = trim(
            ($article['nom']. '')
            . ' '
            . ($article['postnom']. '')
            . ' '
            . ($article['prenom']. '')
        );


        if (empty($employe)) {

            $employe = "Non défini";

        }


        ?>

        <tr>

            <!-- N° -->

            <td>
                <?php echo $numero; ?>
            </td>


            <!-- DATE -->

            <td>
                <?php
                echo htmlspecialchars(
                    $article['date']
                );
                ?>
            </td>


            <!-- ARTICLE -->

            <td>
                <?php
                echo htmlspecialchars(
                    $article['nom_article']
                );
                ?>
            </td>


            <!-- NOMBRE -->

            <td>
                <?php
                echo htmlspecialchars(
                    $article['nombre_article']
                );
                ?>
            </td>


            <!-- PRIX -->

            <td>
                <?php
                echo number_format(
                    $article['prix_article'],
                    2,
                    ',',
                    ' '
                );
                ?>
            </td>


            <!-- DEVISE -->

            <td>
                <?php
                echo htmlspecialchars(
                    $article['nom_devise']
                  
                );
                ?>
            </td>


            <!-- STOCK -->

            <td>
                <?php
                echo htmlspecialchars(
                    $article['nom_produit']
                   
                );
                ?>
            </td>


            <!-- EMPLOYÉ -->

            <td>
                <?php
                echo htmlspecialchars(
                    $employe
                );
                ?>
            </td>


            <!-- ACTIONS -->

            <td>

                <a
                    href="modifier.php?id=<?php
                        echo $article['id_article'];
                    ?>"
                    class="btn-modifier"
                >
                    Modifier
                </a>


                <a
                    href="supprimer.php?id=<?php
                        echo $article['id_article'];
                    ?>"
                    class="btn-supprimer"
                    onclick="
                        return confirm(
                            'Voulez-vous vraiment supprimer cet article ?'
                        );
                    "
                >
                    Supprimer
                </a>

            </td>


        </tr>

        <?php

        $numero++;

    }

} else {

    ?>

    <tr>

        <td
            colspan="9"
            class="aucun"
        >

            Aucun article trouvé.

        </td>

    </tr>

    <?php

}

?>


</tbody>


</table>


</div>


<br>


<a
    href="ajouter.php"
    class="btn-ajouter"
>
    + Ajouter un nouvel article
</a>


</div>


</body>

</html>
