<?php
// Démarrage de la session pour pouvoir y accéder
session_start();

// Libère toutes les variables de session
session_unset();

// Détruit la session
session_destroy();

// Redirection vers l'index ou la page de connexion
header('Location: Dashboard.php'); 
exit();
?>
