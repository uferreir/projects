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
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
</head>
<?php
$vConn = new PDO('pgsql:host=tuxa.sme.utc;port=5432;dbname=dbnf17p159', 'nf17p159', 'SCJ8aveB');
$msg = '<a href="/~nf17p159/déconnexion.php">Déconnexion</a> <br/>';
echo $msg;
$query="SELECT Livre.nom as ln, Auteur.nom as an, Auteur.prénom as ap, date_parution,résumé, lien_contenu FROM Livre LEFT JOIN Ecrit ON Livre.lien_contenu=Ecrit.livre RIGHT JOIN Auteur ON Auteur.id=Ecrit.auteur LEFT JOIN Télécharge ON Livre.lien_contenu=Télécharge.livre WHERE télécharge.utilisateur='$name'";
$result = $vConn->prepare($query);
$result->execute();// écriture, exécution et test de la requête
if (!$result) {
  echo "Une erreur s'est produite.\n";
  exit;
}
while($row = $result->fetch(PDO::FETCH_ASSOC)){
echo "{$row['ln']} <br/> {$row['ap']} {$row['an']} <br/> {$row['date_parution']}<br/>{$row['résumé']}<br/>";
  echo "<form method='post' action='telechargement.php'>
      <p><input type='hidden' name='telechargement' value='$row[lien_contenu]'/>
      <input type='submit' value='Télécharger' name='envoyer'> <br/></p>
  </form><br/>";
}
echo "<a href='/~nf17p159/page_accueil.php'>Retour à la page d'accueil</a>";
$vConn=null;
?>
</head>
