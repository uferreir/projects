<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
</head>
<p><a href='/~nf17p159/compte.php'>Mon Compte</a></p>
<form method="post" action="auth.php">
    <p><label>Identifiant :</label>
        <input type="text" name="login" placeholder="identifiant" size="20" maxlength="50"/>
        <input type="password" name="mdp" placeholder="mot de passe" size="20" maxlength="50"/>
        <input type="submit" value="Envoyer" name="envoyer"> <br/></p>
</form>
<p> <a href='/~nf17p159/nouveau_compte_formulaire.html'>Créer un compte</a></p>
<form method="post" action="recherche.php">
    <label><h2>Rechercher :</h2></label>
    <p>    <input type="text" name="recherche" placeholder="Entrer un titre d'ouvrage ou un auteur" size="30" maxlength="50"/></p>
    <p><input type="submit" value="Envoyer" name="envoyer"> <br/></p>
</form>
<?php
    $vConn = new PDO('pgsql:host=tuxa.sme.utc;port=5432;dbname=dbnf17p159', 'nf17p159', 'SCJ8aveB');
    $query = "SELECT nom from Thème";
    $result = $vConn->prepare($query);
    $result->execute();// Écriture, exécution et test de la requête
    echo "<h1>Liste des thèmes </h1>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	    echo "
            <form method='post' action='themes.php'>
            <p><input type='submit' value='{$row['nom']}' name='thème'></p>
            </form>
            ";
    }

    $query2 = "SELECT nom from Catégorie";
    $result2 = $vConn->prepare($query2);
    $result2->execute();// Écriture, exécution et test de la requête
    echo "<h1>Liste des catégories</h1>";
    while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
        echo "
            <form method='post' action='categories.php'>
            <p><input type='submit' value='{$row2['nom']}' name='catégorie'></p>
            </form>
            ";
    }
$vConn=null;
?>
</html>
