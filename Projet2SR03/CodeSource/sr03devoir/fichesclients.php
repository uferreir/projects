<?php
  session_start();
  if (isset($_SESSION['timestamp'])) { // si $_SESSION['timestamp'] existe
    if (time() - $_SESSION['timestamp'] < 600) {
        $_SESSION['timestamp'] = time();
    } else {
        header("Location: myController.php?action=disconnect");
        die;
    }
  } else {
    $_SESSION['timestamp'] = time();
  }
  if (!isset($_SESSION["connected_user"]["id_user"])) {
    header("Location: connexion.php");
      die;
  }
  if($_SESSION["connected_user"]["profil_user"] == "CLIENT") {
      header("Location: accueil.php");
  }
  if(!isset($_SESSION["passageParController"]) || !$_SESSION["passageParController"]) {
      header("Location: myController.php?action=fichesclients");
  }
  $_SESSION["passageParController"] = false;
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Fiches clients</title>
  <link rel="stylesheet" type="text/css" media="all"  href="css/mystyle.css" />
</head>
<body>
    <header>
        <button class="btn-home form-btn" onclick=redirection("accueil.php")>Accueil</button>
        <form method="POST" action="myController.php">
            <input type="hidden" name="action" value="disconnect">
            <input type="hidden" name="loginPage" value="connexion.php?disconnect">
            <button class="btn-logout form-btn">Déconnexion</button>
        </form>

        <h2><?php echo $_SESSION["connected_user"]["prenom"];?> <?php echo $_SESSION["connected_user"]["nom"];?> - Fiches Clients</h2>
    </header>
    <section>
        <article>

          <div class="liste">
            <table>
              <tr><th>Client</th></tr>
              <?php
              foreach ($_SESSION['listeUsers'] as $id => $user) {
                  if ($user['profil_user'] == 'CLIENT') {
                echo '<tr>';
                echo '<td>
                        <div id="client" onclick=showDetails("infos'.$user["id_user"].'")>
                        '.$user['nom'].' '.$user['prenom'].'
                        </div>
                        <div id="infos'.$user["id_user"].'" class="hidden">
                            Numéro de compte : '.$user['numero_compte'].'<br/>
                            Solde du compte : '.$user['solde_compte'].'€<br/>
                            <form method="POST" action="virement.php">
                                <button class="form-btn" name="num_compte_source" value="'.$user["numero_compte"].'">Virement</button>
                            </form>
                        </div>
                    </td>';
                echo '</tr>';
            }
              }
               ?>
            </table>
          </div>

        </article>
    </section>
</body>
<script src="functions.js"></script>
</html>
