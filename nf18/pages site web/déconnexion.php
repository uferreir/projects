<?php
session_start();
session_unset();
session_destroy();
header('location: /~nf17p159/page_accueil.php');
exit;
?>
