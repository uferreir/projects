<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
</head>
<?php
$vConn = new PDO('pgsql:host=tuxa.sme.utc;port=5432;dbname=dbnf17p159', 'nf17p159', 'SCJ8aveB');
$mail=$_POST['mail'];
$nom=$_POST['nom'];
$prenom=$_POST['prenom'];
$mdp=$_POST['mdp'];
$naissance=$_POST['naissance'];
$query = "INSERT INTO Utilisateur VALUES ('$mail', '$nom','$prenom','$mdp','$naissance','FALSE')";
$result = $vConn->prepare($query);
$result->execute();// Écriture, exécution et test de la requête
if ($mail=="" || $nom=="" || $prenom=="" || $mdp=="" || $naissance=="")
{header('Refresh:0; url=/~nf17p159/nouveau_compte_formulaire.html');
 exit();
}
if ($result->fetch() == false)
{
    echo "Impossible de créer le compte, veuillez vérifier vos informations.<br/> <a href='/~nf17p159/nouveau_compte_formulaire.html'>Réessayer</a><br/><a href='/~nf17p159/page_accueil.php'>Retour à la page d''accueil</a>";
}

// Si ça n'est pas false, maintenant le fetch se fait sur la deuxième ligne
else
{
    echo "Félicitation, votre compte a bien été créé !<br/><a href='/~nf17p159/page_accueil.php'>Retour à la page d'accueil</a>";}
?>
