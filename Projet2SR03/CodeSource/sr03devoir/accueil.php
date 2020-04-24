<?php
  session_start();
  if (isset($_SESSION['timestamp'])) {
    if (time() - $_SESSION['timestamp'] < 600) {
        $_SESSION['timestamp'] = time();
    } else {
        header("Location: myController.php?action=disconnect");
        die;
    }
  } else {
    $_SESSION['timestamp'] = time();
  }
  if (! isset($_SESSION["connected_user"]["id_user"])) {
    header("Location: connexion.php");
      die;
  }
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Mon Compte</title>
  <link rel="stylesheet" type="text/css" media="all"  href="css/mystyle.css" />
</head>
<body>
    <header>
        <form method="POST" action="myController.php">
            <input type="hidden" name="action" value="disconnect">
            <input type="hidden" name="loginPage" value="connexion.php?disconnect">
            <button class="btn-logout form-btn">Déconnexion</button>
        </form>

        <h2><?php echo $_SESSION["connected_user"]["prenom"];?> <?php echo $_SESSION["connected_user"]["nom"];?> - Mon compte</h2>
    </header>

    <section>

        <article>
          <div class="fieldset">
              <div class="fieldset_label">
                  <span>Vos informations personnelles</span>
              </div>
              <div class="field">
                  <label>Login : </label><span><?php echo $_SESSION["connected_user"]["login"];?></span>
              </div>
              <div class="field">
                  <label>Profil : </label><span><?php echo $_SESSION["connected_user"]["profil_user"];?></span>
              </div>
          </div>
        </article>

        <article>
          <div class="fieldset">
              <div class="fieldset_label">
                  <span>Votre compte</span>
              </div>
              <div class="field">
                  <label>N° compte : </label><span><?php echo $_SESSION["connected_user"]["numero_compte"];?></span>
              </div>
              <div class="field">
                  <label>Solde : </label><span><?php echo $_SESSION["connected_user"]["solde_compte"];?> &euro;</span>
              </div>
          </div>
        </article>


        <article>
            <div class="fieldset">
                <div class="fieldset_label">
                    <span>Menu</span>
                </div>
                <div class="field">
                    <label>Messagerie :</label> <button class="form-btn" onclick=redirection("myController.php?action=msglist&userid=<?php echo $_SESSION["connected_user"]["id_user"];?>")>accéder à la messagerie</button>
                </div>
                <div class="field">
                    <label>Effectuer un virement :</label>
                    <form method="POST" action="virement.php">
                        <button class="form-btn" name="num_compte_source" value="<?php echo $_SESSION["connected_user"]["numero_compte"]; ?>">Virement</button>
                    </form>
                </div>
                <?php
                if ($_SESSION['connected_user']['profil_user'] == 'EMPLOYE') {
                    echo'<div class="field">
                        <label>Fiche client :</label> <button class="form-btn" onclick=redirection("myController.php?action=fichesclients")>accéder aux fiches clients</button>
                    </div>';
                }
                ?>
            </div>
        </article>

    </section>
</body>
<script src="functions.js"></script>
</html>
