<?php
session_start();
$name=$_SESSION['name'];
$pass=$_SESSION['pass'];
if ($name=="" || $pass=="") {
        // Renvoie l'utilisateur à la racine du serveur
        header("Location: /~nf17p159/page_accueil.php");
        // Met fin au script par mesure de sécurité
        die();
    }
$vConn = new PDO('pgsql:host=tuxa.sme.utc;port=5432;dbname=dbnf17p159', 'nf17p159', 'SCJ8aveB');
$var=$_POST['telechargement'];
print $var;
$query="INSERT INTO Télécharge VALUES ('$var','$name')";
$result = $vConn->prepare($query);
$result->execute();// Écriture, exécution et test de la requête
header('location:'.$var);
$vConn=null;
?>
