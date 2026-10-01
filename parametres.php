<?php
include("header.php");
include("sidebar.php");
include("connexion.php");
?>

<div class="main-content">

<div class="container-fluid">

<h2 class="mb-4">
<i class="fas fa-cogs"></i>
Paramètres
</h2>

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h4>Configuration de l'application</h4>
</div>

<div class="card-body">

<form action="" method="post">

<div class="row">

<div class="col-md-6 mb-3">
<label>Nom de l'entreprise</label>
<input type="text" class="form-control" value="Mon Entreprise">
</div>

<div class="col-md-6 mb-3">
<label>Téléphone</label>
<input type="text" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Adresse</label>
<input type="text" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Devise</label>
<select class="form-control">
<option>USD ($)</option>
<option>CDF (FC)</option>
<option>EUR (€)</option>
</select>
</div>

<div class="col-md-6 mb-3">
<label>Langue</label>
<select class="form-control">
<option>Français</option>
<option>English</option>
</select>
</div>

<div class="col-md-12 mb-3">
<label>Logo de l'entreprise</label>
<input type="file" class="form-control">
</div>

<div class="col-md-12">

<button type="submit" class="btn btn-success">
<i class="fas fa-save"></i>
Enregistrer
</button>

<button type="reset" class="btn btn-secondary">
<i class="fas fa-undo"></i>
Annuler
</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

<?php
include("footer.php");
?>

