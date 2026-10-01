<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");
$id = $_GET['id'];

$req = mysqli_query($conn,"SELECT * FROM approvisionnement WHERE id_appro='$id'");
$data = mysqli_fetch_assoc($req);

if(isset($_POST['modifier']))
{
    $nom = $_POST['date'];
    $postnom = $_POST['produit'];
    $prenom = $_POST['quantite'];
    $fonction = $_POST['prix_achat'];
    $telephone = $_POST['id_fournisseur'];
    $mail = $_POST['id_devise'];
    $salaire = $_POST['id_employe'];

    mysqli_query($conn,"
    UPDATE approvisionnement SET
    date ='$nom',
    produit='$postnom',
    quantite='$prenom',
    prix_achat='$fonction',
    id_fournisseur='$telephone',  
    id_devise='$mail',
    id_employe='$salaire'
    WHERE id_appro='$id'
    ");

    header("Location: liste.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Modifier opération</title>
</head>
<body>

<h2>Modifier une opération</h2>

<form method="POST">
Date :
<input type="date" name="date"
value="<?php echo $data['date']; ?>" required>
<br><br>

produit :
<input type="text" name="produit"
value="<?php echo $data['produit']; ?>" required>
<br><br>

quantite :
<input type="number" name="quantite"
value="<?php echo $data['quantite']; ?>">
<br><br>


prix_achat:
<input type="number" step="0.01" name="prix_achat"
value="<?php echo $data['prix_achat']; ?>">
<br><br>

Fournisseur :

<select
    name="id_fournisseur"
    required
>

    <option value="">
        -- Sélectionner un Fournisseur --
    </option>


<?php

$sql_employe = "
    SELECT id_fournisseur, nom, adresse
    FROM fournisseur
    ORDER BY nom ASC
";

$resultat_employe = mysqli_query(
    $conn,
    $sql_employe
);


if (!$resultat_employe) {

    echo "
        <option value=''>
            Erreur de chargement des Fournisseur
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
                     $employe['adresse'];


?>

    <option
        value="<?php echo $employe['id_fournisseur']; ?>"
        <?php echo ($data['id_fournisseur'] == $employe['id_fournisseur'])
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

</select><br><br>

Devise :

<select
    name="id_devise"
    required
>

    <option value="">
        -- Sélectionner un Devise --
    </option>


<?php

$sql_employe = "
    SELECT id_devise, nom_devise
    FROM devise
    ORDER BY nom_devise ASC
";

$resultat_employe = mysqli_query(
    $conn,
    $sql_employe
);


if (!$resultat_employe) {

    echo "
        <option value=''>
            Erreur de chargement des Devise
        </option>
    ";

} else {

    while (
        $employe = mysqli_fetch_assoc(
            $resultat_employe
        )
    ) {

        $nom_complet =
            $employe['nom_devise'];

?>

    <option
        value="<?php echo $employe['id_devise']; ?>"
        <?php echo ($data['id_devise'] == $employe['id_devise'])
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

</select><br><br>
Employe :
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
        <?php echo ($data['id_employe'] == $employe['id_employe'])
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

</select><br><br>
<input type="submit" name="modifier" value="Modifier">

<a href="liste.php">Retour</a>

</form>

</body>
</html>
