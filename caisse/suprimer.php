<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

if(!isset($_GET['id']))
{
    header("Location: liste.php");
    exit();
}

$id = intval($_GET['id']);

// Vérifier si l'opération existe
$verification = mysqli_query($conn, "SELECT * FROM caisse WHERE id='$id'");

if(mysqli_num_rows($verification) == 0)
{
    header("Location: liste.php?msg=introuvable");
    exit();
}

// Suppression
$supprimer = mysqli_query($conn, "DELETE FROM caisse WHERE id='$id'");

if($supprimer)
{
    header("Location: liste.php?msg=supprime");
}
else
{
    echo "Erreur lors de la suppression : " . mysqli_error($conn);
}
?>
