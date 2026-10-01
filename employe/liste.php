<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Liste des opérations de Employe</title>

<style>
table{
    border-collapse:collapse;
    width:100%;
}
th,td{
    border:1px solid black;
    padding:8px;
    text-align:center;
}
th{
    background:#f2f2f2;
}
a{
    text-decoration:none;
}
</style>

</head>
<body>

<h2>Liste des opérations de Employe</h2>

<p>
<a href="index.php">Nouvelle opération</a>
</p>

<table>
<tr>
    <th>ID</th>
    <th>Date</th>
    <th>nom</th>
    <th>postnom</th>
    <th>prenom</th>
    <th>fonction</th>
    <th>telephone</th>
    <th>mail</th>
    <th>salaire</th>
    <th>Actions</th>
</tr>

<?php

$req = mysqli_query($conn,"SELECT * FROM employe ORDER BY id_employe DESC");

while($data = mysqli_fetch_assoc($req))
{
?>
<tr>
    <td><?php echo $data['id_employe']; ?></td>
    <td><?php echo $data['date']; ?></td>
    <td><?php echo $data['nom']; ?></td>
    <td><?php echo $data['postnom']; ?></td>
    <td><?php echo $data['prenom']; ?></td>
    <td><?php echo $data['fonction']; ?></td>
    <td><?php echo $data['telephone']; ?></td>
    <td><?php echo $data['mail']; ?></td>
    <td><?php echo $data['salaire']; ?></td>
   

    <td>
        <a href="modifier.php?id=<?php echo $data['id_employe']; ?>">
        Modifier
        </a>

        |

        <a href="suprimer.php?id=<?php echo $data['id_employe']; ?>"
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
