<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); // retour vers la page d'accueil
exit;
?>
