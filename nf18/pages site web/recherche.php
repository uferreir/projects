<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
</head>
<?php
$vConn = new PDO('pgsql:host=tuxa.sme.utc;port=5432;dbname=dbnf17p159', 'nf17p159', 'SCJ8aveB');
$var=$_POST['recherche'];
if ($var=="") {
        // Renvoie l'utilisateur à la racine du serveur
        header("Location: /~nf17p159/page_accueil.php");
        // Met fin au script par mesure de sécurité
        die();
    }
$query = "SELECT an, ap, ln, résumé, date_parution, lien_contenu from Recherche where concaténation ILIKE '%$var%'";
$result = $vConn->prepare($query);
$result->execute();// Écriture, exécution et test de la requête
echo "Liste des résultats<br/>";
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
