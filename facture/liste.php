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

/* Recherche */
$recherche = "";

if (isset($_GET['recherche'])) {
    $recherche = mysqli_real_escape_string($conn, $_GET['recherche']);
}

/* Requête */
$sql = "
    SELECT 
        facture.*,
        vente.produit,
        client.nom,
        employe.nom,
        employe.postnom,
        employe.prenom
    FROM facture
    LEFT JOIN vente
        ON facture.id_vente = vente.id_vente
    LEFT JOIN client
        ON facture.id_client = client.id_client
    LEFT JOIN employe 
        ON facture.id_employe = employe.id_employe
";

if (!empty($recherche)) {
    $sql .= "
        WHERE facture.designation LIKE '%$recherche%'
        OR client.nom LIKE '%$recherche%'
        OR employe.nom LIKE '%$recherche%'
        OR employe.postnom LIKE '%$recherche%'
        OR employe.prenom LIKE '%$recherche%'
    ";
}

$sql .= " ORDER BY facture.id_facture DESC";

$resultat = mysqli_query($conn, $sql);

if (!$resultat) {
    die("Erreur SQL : " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des factures</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 20px;
        }

        h2 {
            text-align: center;
        }

        .recherche {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            padding: 10px;
            width: 300px;
        }

        button {
            padding: 10px 15px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #343a40;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .modifier {
            background: #ffc107;
            color: black;
            padding: 7px 10px;
            text-decoration: none;
            border-radius: 4px;
        }

        .imprimer {
            background: #007bff;
            color: white;
            padding: 7px 10px;
            text-decoration: none;
            border-radius: 4px;
        }

    </style>
</head>

<body>

<h2>LISTE DES FACTURES</h2>

<div class="recherche">

    <form method="GET">

        <input
            type="text"
            name="recherche"
            placeholder="Rechercher une facture..."
            value="<?php echo htmlspecialchars($recherche); ?>"
        >

        <button type="submit">
            Rechercher
        </button>

    </form>

</div>

<table>

    <tr>

        <th>N°</th>
        <th>Date</th>
        <th>Désignation</th>
        <th>Quantité</th>
        <th>Prix unitaire</th>
        <th>Total</th>
        <th>Devise</th>
        <th>Vente</th>
        <th>Client</th>
        <th>Employé</th>
        <th>Actions</th>

    </tr>

<?php

$total_general = 0;

if (mysqli_num_rows($resultat) > 0) {

    while ($facture = mysqli_fetch_assoc($resultat)) {

        $total_general += (float)$facture['total'];

        $nom_vente = 
            $facture['produit'];

        $nom_client = 
            $facture['nom'];
        $nom_employe =
            $facture['nom'] . " " .
            $facture['postnom'] . " " .
            $facture['prenom'];

?>

    <tr>

        <td>
            <?php echo $facture['id_facture']; ?>
        </td>

        <td>
            <?php echo htmlspecialchars($facture['date']); ?> 
        </td>

        <td>
            <?php echo htmlspecialchars($facture['desingation']); ?>
        </td>

        <td>
            <?php echo $facture['QT']; ?>
        </td>

        <td>
            <?php echo number_format($facture['PU'], 2, ',', ' '); ?>
        </td>

        <td>
            <strong>
                <?php echo number_format($facture['total'], 2, ',', ' '); ?>
            </strong>
        </td>

        <td>
            <?php echo $facture['devise']; ?>
        </td>

        <td>
            <?php echo htmlspecialchars($nom_vente); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($nom_client); ?>
        </td>



        <td>
            <?php echo htmlspecialchars($nom_employe); ?>
        </td>

        

        <td>
        <a href="modifier.php?id=<?php echo $facture['id_facture']; ?>">
        Modifier
        </a>

        |

        <a href="suprimer.php?id=<?php echo $facture['id_facture']; ?>"
        onclick="return confirm('Voulez-vous supprimer cette opération ?')">
        Supprimer
        </a>

        |

        <a href="Facture_impression.php?id=<?php echo $facture['id_facture']; ?>">
        Imprimer
        </a>


        </td>

    </tr>

<?php

    }

} else {

?>

    <tr>
        <td colspan="8">
            Aucune facture trouvée.
        </td>
    </tr>

<?php

}

?>

    <tr>

        <td colspan="5">
            <strong>TOTAL GÉNÉRAL</strong>
        </td>

        <td>
            <strong>
                <?php echo number_format($total_general, 2, ',', ' '); ?>
            </strong>
        </td>

        <td colspan="2"></td>

    </tr>

</table>

</body>
</html>
