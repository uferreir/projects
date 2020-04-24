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
?>

<!doctype html>

<?php
    if (isset($_REQUEST["num_compte_source"])) {
        $_SESSION["num_compte_source"] = $_REQUEST["num_compte_source"];
    } else if (isset($_REQUEST["trf_ok"]) || isset($_REQUEST["bad_mt"])){
        //on ne fait rien
    } else {
        $_SESSION["num_compte_source"] = $_SESSION["connected_user"]["numero_compte"];
    }
?>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Mon Compte</title>
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
        <h2><?php echo $_SESSION["connected_user"]["prenom"];?> <?php echo $_SESSION["connected_user"]["nom"];?> - Virements</h2>
    </header>

    <section>


        <article>
        <form method="POST" action="myController.php">
          <input type="hidden" name="action" value="transfert">
          <div class="fieldset">
              <div class="fieldset_label">
                  <span>Transférer de l'argent</span>
              </div>
                <span>Virement à partir du compte <?php echo $_SESSION["num_compte_source"];?></span>
              <div class="field">
                  <label>N° compte destinataire : </label>
                  <select name="destination">
                    <?php
                    foreach ($_SESSION['listeUsers'] as $id => $user) {
                        if ($_SESSION["num_compte_source"] != $user["numero_compte"]) {
                            echo '<option value="'.$user['numero_compte'].'">'.$user['nom'].' '.$user['prenom'].'</option>';
                        }
                    }
                    ?>
                  </select>
              </div>
              <div class="field">
                  <label>Montant à transférer : </label><input type="text" size="10" name="montant">
              </div>

              <button class="form-btn" >Transférer</button>
              <?php
              if (isset($_REQUEST["trf_ok"])) {
                echo '<p>Virement effectué avec succès.</p>';
              }
              if (isset($_REQUEST["bad_mt"])) {
                echo '<p>Le montant saisi est incorrect : '.$_REQUEST["bad_mt"].'</p>';
              }
              ?>
          </div>
        </form>
        </article>

    </section>
</body>
<script src="functions.js"></script>
</html>
