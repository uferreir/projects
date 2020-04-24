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
  if(!isset($_SESSION["passageParController"]) || $_SESSION["passageParController"] == false) {
      header("Location: myController.php?action=msglist&userid=".$_SESSION['connected_user']['id_user']);
  }
  $_SESSION["passageParController"] = false;

?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Messages</title>
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

        <h2><?php echo $_SESSION["connected_user"]["prenom"];?> <?php echo $_SESSION["connected_user"]["nom"];?> - Messages reçus</h2>
    </header>

    <section>
        <article>

          <div class="liste">
            <table>
              <tr><th>Expéditeur</th><th>Sujet</th><th>Message</th></tr>
              <?php
              foreach ($_SESSION['messagesRecus'] as $cle => $message) {
                echo '<tr>';
                echo '<td>'.$message['nom'].' '.$message['prenom'].'</td>';
                echo '<td>'.$message['sujet_msg'].'</td>';
                echo '<td>'.$message['corps_msg'].'</td>';
                echo '</tr>';
              }
               ?>
            </table>
          </div>

        </article>

        <article>
        <form method="POST" action="myController.php">
          <input type="hidden" name="action" value="sendmsg">
          <div class="fieldset">
              <div class="fieldset_label">
                  <span>Envoyer un message</span>
              </div>
              <div class="field">
                  <label>Destinataire : </label>
                  <select name="to">
                    <?php
                    foreach ($_SESSION['listeUsers'] as $id => $user) {
                        if ($user['id_user'] != $_SESSION['connected_user']['id_user']) {
                            if($_SESSION['connected_user']['profil_user'] == 'CLIENT') {
                                if($user['profil_user'] == 'EMPLOYE') {
                                    echo '<option value="'.$id.'">'.$user['nom'].' '.$user['prenom'].'</option>';
                                }
                            } else {
                                echo '<option value="'.$id.'">'.$user['nom'].' '.$user['prenom'].'</option>';
                            }
                        }
                    }
                    ?>
                  </select>
              </div>
              <div class="field">
                  <label>Sujet : </label><input type="text" size="20" name="sujet">
              </div>
              <div class="field">
                  <label>Message : </label><textarea name="corps" cols="25" rows="3""></textarea>
              </div>
              <button class="form-btn">Envoyer</button>
              <?php
              if (isset($_REQUEST["msg_ok"])) {
                echo '<p>Message envoyé avec succès.</p>';
              }
              ?>
          </div>
        </form>
        </article>
    </section>
</body>
<script src="functions.js"></script>
</html>
