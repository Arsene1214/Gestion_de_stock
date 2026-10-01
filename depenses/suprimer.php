<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");
$id = $_GET['id'];

mysqli_query($conn,
"DELETE FROM depenses WHERE id_depense='$id'");

header("Location: liste.php");
?>
