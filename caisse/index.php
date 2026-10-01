<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$base = "cici";

$conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base);
mysqli_set_charset($conn, "utf8");

// Entrées USD
$req = mysqli_query($conn,"SELECT SUM(montant) AS total FROM caisse
WHERE type_operation='ENTREE' AND devise='USD'");
$row = mysqli_fetch_assoc($req);
$entree_usd = $row['total'];
if($entree_usd=="") $entree_usd=0;

// Sorties USD
$req = mysqli_query($conn,"SELECT SUM(montant) AS total FROM caisse
WHERE type_operation='SORTIE' AND devise='USD'");
$row = mysqli_fetch_assoc($req);
$sortie_usd = $row['total'];
if($sortie_usd=="") $sortie_usd=0;

// Entrées CDF
$req = mysqli_query($conn,"SELECT SUM(montant) AS total FROM caisse
WHERE type_operation='ENTREE' AND devise='CDF'");
$row = mysqli_fetch_assoc($req);
$entree_cdf = $row['total'];
if($entree_cdf=="") $entree_cdf=0;

// Sorties CDF
$req = mysqli_query($conn,"SELECT SUM(montant) AS total FROM caisse
WHERE type_operation='SORTIE' AND devise='CDF'");
$row = mysqli_fetch_assoc($req);
$sortie_cdf = $row['total'];
if($sortie_cdf=="") $sortie_cdf=0;

// Soldes
$solde_usd = $entree_usd - $sortie_usd;
$solde_cdf = $entree_cdf - $sortie_cdf;

// Nombre d'opérations
$req = mysqli_query($conn,"SELECT COUNT(*) AS total FROM caisse");
$row = mysqli_fetch_assoc($req);
$total_operation = $row['total'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Tableau de bord</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
margin:20px;
}

h1{
text-align:center;
color:#003366;
}

.cards{
display:flex;
flex-wrap:wrap;
justify-content:space-between;
}

.card{
width:23%;
background:white;
padding:20px;
margin-bottom:20px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,.1);
text-align:center;
}

.card h2{
margin:0;
font-size:18px;
color:#0066cc;
}

.card p{
font-size:25px;
font-weight:bold;
}

table{
width:100%;
background:white;
border-collapse:collapse;
}

table th{
background:#0066cc;
color:white;
padding:10px;
}

table td{
padding:8px;
border:1px solid #ddd;
}

a{
text-decoration:none;
}

.btn{
padding:10px;
background:#28a745;
color:white;
border-radius:5px;
}

</style> 

</head>

<body>

<h1>TABLEAU DE BORD CAISSE</h1>

<div class="cards">

<div class="card">
<h2>Entrées USD</h2>
<p><?php echo number_format($entree_usd,2)?> USD</p>
</div>

<div class="card">
<h2>Sorties USD</h2>
<p><?php echo number_format($sortie_usd,2)?> USD</p>
</div>

<div class="card">
<h2>Solde USD</h2>
<p><?php echo number_format($solde_usd,2)?> USD</p>
</div>

<div class="card">
<h2>Solde CDF</h2>
<p><?php echo number_format($solde_cdf,2)?> CDF</p>
</div>

<div class="card">
<h2>Entrées CDF</h2>
<p><?php echo number_format($entree_cdf,2)?> CDF</p>
</div>

<div class="card">
<h2>Sorties CDF</h2>
<p><?php echo number_format($sortie_cdf,2)?> CDF</p>
</div>

<div class="card">
<h2>Opérations</h2>
<p><?php echo $total_operation?></p>
</div>

<div class="card">
<a href="liste.php" class="btn">voir liste</a>
</div>

<div class="card">
<a href="ajouter.php" class="btn">Nouvelle opération</a>
</div>

</div>


<h2>Dernières opérations</h2>

<table>

<tr>

<th>Date</th>
<th>Référence</th>
<th>Type</th>
<th>Catégorie</th>
<th>Montant</th>
<th>Devise</th>

</tr>

<?php

$liste=mysqli_query($conn,"SELECT * FROM caisse ORDER BY id DESC LIMIT 10");

while($l=mysqli_fetch_assoc($liste))
{

?>

<tr>

<td><?php echo $l['date']?></td>

<td><?php echo $l['reference_operation']?></td>

<td><?php echo $l['type_operation']?></td>

<td><?php echo $l['categorie']?></td>

<td><?php echo number_format($l['montant'],2)?></td>

<td><?php echo $l['devise']?></td>

</tr>

<?php

}

?>

</table>

</body>

</html>
