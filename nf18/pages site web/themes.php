<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
</head>
<?php
$vConn = new PDO('pgsql:host=****;port=*****;dbname=*****', '*****', '******');
$var=$_POST['thème'];
if ($var=="") {
        // Renvoie l'utilisateur à la racine du serveur
        header("Location: /~nf17p159/page_accueil.php");
        // Met fin au script par mesure de sécurité
        die();
    }
$query = "SELECT Livre.nom as ln, Auteur.nom as an, Auteur.prénom as ap, date_parution,résumé, lien_contenu FROM Livre LEFT JOIN Ecrit ON Livre.lien_contenu=Ecrit.livre RIGHT JOIN Auteur ON Auteur.id=Ecrit.auteur LEFT JOIN Appartient ON Livre.lien_contenu=Appartient.livre Where Appartient.thème='$var'";
$result = $vConn->prepare($query);
$result->execute();// Écriture, exécution et test de la requête
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	echo "{$row['ln']} <br/> {$row['ap']} {$row['an']} <br/> {$row['date_parution']}<br/>{$row['résumé']}<br/>";
    echo "<form method='post' action='telechargement.php'>
        <p><input type='hidden' name='telechargement' value='$row[lien_contenu]'/>
        <input type='submit' value='Télécharger' name='envoyer'> <br/></p>
    </form><br/>";
}
echo "<a href='/~nf17p159/page_accueil.php'>Retour à la page d'accueil</a>";
$vConn=null;
?>
</html>
