<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";
$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

$recherche = "";

if(isset($_GET['recherche']))
{
    $recherche = mysqli_real_escape_string($conn, $_GET['recherche']) ;
}

$sql = "SELECT * FROM dette
        WHERE id_client LIKE '%$recherche%'
        OR id_employe LIKE '%$recherche%'
        ORDER BY id_dette DESC";

$result = mysqli_query($conn, $sql);
if (!$result){
    die("Erreur dans la requete de liste : " .mysqli_error($conn));
}

$total = mysqli_query($conn,"
SELECT SUM(montant_dette) AS total_dette
FROM dette
");

$somme = mysqli_fetch_assoc($total);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Liste des Dette</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>
<body>

<div class="container mt-4">

<h2 class="text-center mb-4">
Liste des Dette
</h2>

<div class="mb-3">
<a href="index.php" class="btn btn-success">
Nouvelle Dette
</a>
</div>

<form method="GET" class="mb-3">

<div class="row">

<div class="col-md-10">
<input type="text"
name="recherche"
class="form-control"
placeholder="Recherche id_client ou id_employe"
value="<?php echo $recherche; ?>">
</div>

<div class="col-md-2">
<button class="btn btn-primary w-100">
Rechercher
</button>
</div>

</div>

</form>

<div class="alert alert-info">

<strong>Total des Dette :</strong>

<?php echo number_format($somme['total_dette'],2); ?>

</div>

<div class="table-responsive">

<table class="table table-bordered table-striped table-hover">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>Date</th>
<th>montant_dette</th>
<th>Devise</th>
<th>nature_dete</th>
<th>Actions</th>
</tr>

</thead>

<tbody>

<?php
while($row=mysqli_fetch_assoc($result))
{
?>
<tr>

<td><?php echo $row['id_dette']; ?></td>

<td><?php echo $row['date']; ?></td>

<td><?php echo number_format($row['montant_dette'],2); ?></td>
<td><?php echo $row['devise']; ?></td>

<td><?php echo $row['nature_dete']; ?></td>



<td>

<a href="modifier.php?id=<?php echo $row['id_dette']; ?>"
class="btn btn-primary btn-sm">
Modifier
</a>

<a href="suprimer.php?id=<?php echo $row['id_dette']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Voulez-vous vraiment supprimer cette dépense ?');">
Supprimer
</a>

</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</body>
</html>