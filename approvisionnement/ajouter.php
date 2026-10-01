<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

if(isset($_POST['enregistrer']))
{
    $nom = $_POST['date'];
    $postnom = $_POST['produit'];
    $prenom = $_POST['quantite'];
    $fonction = $_POST['prix_achat'];
    $telephone = $_POST['id_fournisseur'];
    $mail = $_POST['id_devise'];
    $salaire = $_POST['id_employe'];

    $sql = "INSERT INTO approvisionnement
    (date, produit, quantite, prix_achat, id_fournisseur, id_devise, id_employe)
    VALUES
    ('$nom','$postnom','$prenom','$fonction','$telephone','$mail','$salaire')";

if(mysqli_query($conn,$sql)){  

    header("Location: liste.php");
    exit();
}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Gestion approvisionnement</title>
</head>

<body>

<h2>Nouvelle Opération de approvisionnement</h2>

<form action="ajouter.php" method="POST">

date_appro : <br>
<input type="date" name="date" required><br><br>

produit : <br>
<input type="text"  name="produit" required><br><br>

quantite : <br>
<input type="int" name="quantite"><br><br>
prix_achat : <br>
<input type="number" step="0.01" name="prix_achat"><br><br>


<!-- FOURNISSEUR -->

Fournisseur : <br>

<select name="id_fournisseur" required>

    <option value="">
        -- Sélectionner un Fournisseur --
    </option>

    <?php

    $sql_employe = "
        SELECT id_fournisseur, nom, adresse
        FROM Fournisseur
        ORDER BY nom ASC
    ";

    $resultat = mysqli_query($conn, $sql_employe);

    if ($resultat) {

        while ($employe = mysqli_fetch_assoc($resultat)) {

            $nom_complet =
                $employe['nom'] . " " .
                $employe['adresse'];

            ?>

            <option value="<?php echo $employe['id_fournisseur']; ?>">

                <?php echo htmlspecialchars($nom_complet); ?>

            </option>

            <?php
        }

    } else {

        echo '<option value="">Erreur de chargement des fournisseur</option>';

    }

    ?>

</select><br><br>

<!-- Devise -->

Devise : <br>

<select name="id_devise" required>

    <option value="">
        -- Sélectionner un Devise --
    </option>

    <?php

    $sql_employe = "
        SELECT id_devise, nom_devise
        FROM devise
        ORDER BY nom_devise ASC
    ";

    $resultat = mysqli_query($conn, $sql_employe);

    if ($resultat) {

        while ($employe = mysqli_fetch_assoc($resultat)) {

            $nom_complet =
                $employe['nom_devise'];
                

            ?>

            <option value="<?php echo $employe['id_devise']; ?>">

                <?php echo htmlspecialchars($nom_complet); ?>

            </option>

            <?php
        }

    } else {

        echo '<option value="">Erreur de chargement des devise </option>';

    }

    ?>

</select><br><br>


    <!-- EMPLOYE -->

    Employé : <br>
    

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

    </select> <br><br>

<input type="submit" name="enregistrer" value="Enregistrer">

</form>

</body>

