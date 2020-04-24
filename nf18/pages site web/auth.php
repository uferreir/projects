<?php
session_start();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
</head>
<?php
if (!$_POST['login']=="" && !$_POST['mdp']=="")
{
  //on récupère les valeurs des champs
$name = $_POST['login'];
$pass = $_POST['mdp'];
// Connexion à la base de données
$vConn = new PDO('pgsql:host=tuxa.sme.utc;port=5432;dbname=dbnf17p159', 'nf17p159', 'SCJ8aveB');
$query = "SELECT mdp from Utilisateur where mail ='$name'";
$result = $vConn->prepare($query);
$result->execute();// écriture, exécution et test de la requête
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row['mdp']==$pass)
	{
    $_SESSION['name'] = $name;
    $_SESSION['pass']= $pass;
    /*echo "
        <form method='post' action='compte.php'>
        <p>
        <input type='hidden' name='nom' value=$name/>
        <input type='hidden' name='mdp' value=$pass/>
        <input type='submit' value='valider' name='valider'>
        </p>
        </form>
        ";*/
    header('Refresh:0; url=/~nf17p159/compte.php');
     exit();
    }
  else {
    //sinon on avertit l'utilisateur
    $msg = 'Votre nom ou votre mot de passe est incorrect<br />';
    $msg .= "<a href='/~nf17p159/page_accueil.php'>Retour à la page d'accueil</a>";
    echo $msg;
        }
  //fermeture de la connexion
  $vConn=null;
}
if ($_POST['login']=="" || $_POST['mdp']=="")
{header('Refresh:0; url=/~nf17p159/page_accueil.php');
 exit();
}
$vConn=null;
?>
</html>
