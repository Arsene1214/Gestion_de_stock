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
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="utf-8">
<title>Liste des opérations d'approvisionnement</title>

<style>
body {
    font-family: Arial, sans-serif;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    border: 1px solid black;
    padding: 8px;
    text-align: center;
}

th {
    background: #f2f2f2;
}

a {
    text-decoration: none;
}

.btn {
    padding: 5px 10px;
}

.modifier {
    color: blue;
}

.supprimer {
    color: red;
}
</style>

</head>

<body>

<h2>Liste des opérations d'approvisionnement</h2>

<p>
    <a href="index.php">➕ Nouvelle opération</a>
</p>

<table>

<tr>
    <th>ID</th>
    <th>Date</th>
    <th>Produit</th>
    <th>Quantité</th>
    <th>Prix d'achat</th>
    <th>Fournisseur</th>
    <th>Devise</th>
    <th>Employé</th>
    <th>Actions</th>
</tr>

<?php

$sql = "
    SELECT 
        approvisionnement.*,

        fournisseur.nom,

        devise.nom_devise,

        employe.nom,
        employe.postnom,
        employe.prenom

    FROM approvisionnement

    LEFT JOIN fournisseur
        ON approvisionnement.id_fournisseur = fournisseur.id_fournisseur

    LEFT JOIN devise
        ON approvisionnement.id_devise = devise.id_devise

    LEFT JOIN employe
        ON approvisionnement.id_employe = employe.id_employe

    ORDER BY approvisionnement.id_appro DESC
";

$req = mysqli_query($conn, $sql);

if (!$req) {
    die("Erreur SQL : " . mysqli_error($conn));
}

while ($data = mysqli_fetch_assoc($req)) {

    $nom_employe = trim(
        ($data['nom'].  '') . ' ' .
        ($data['postnom'].  '') . ' ' .
        ($data['prenom']. '')
    );

?>

<tr>

    <td>
        <?php echo $data['id_appro']; ?>
    </td>

    <td>
        <?php echo htmlspecialchars($data['date']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($data['produit']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($data['quantite']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($data['prix_achat']); ?>
    </td>

    <td>
        <?php
        echo htmlspecialchars(
            $data['nom']
        );
        ?>
    </td>

    <td>
        <?php
        echo htmlspecialchars(
            $data['nom_devise']
        );
        ?>
    </td>

    <td>
        <?php
        echo htmlspecialchars(
            $nom_employe 
        );
        ?>
    </td>

    <td>

        <a class="modifier"
           href="modifier.php?id=<?php echo $data['id_appro']; ?>">
            Modifier
        </a>

        |

        <a class="supprimer"
           href="suprimer.php?id=<?php echo $data['id_appro']; ?>"
           onclick="return confirm('Voulez-vous supprimer cette opération ?')">
            Supprimer
        </a>

    </td>

</tr>

<?php
}
?>

</table>

</body>
</html>

