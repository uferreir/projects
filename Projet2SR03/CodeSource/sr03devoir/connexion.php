<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Connexion</title>
  <link rel="stylesheet" type="text/css" media="all"  href="css/mystyle.css" />
</head>
<body>
  <header>
    <h1>Connexion</h1>
  </header>

  <section>
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
    if ((isset($_SESSION['failed_authentification']) && $_SESSION[failed_authentification] < 3) || (!isset($_SESSION['failed_authentification']))) {
        echo '<div class="login-page">
          <div class="form">
              <form method="POST" action="myController.php">
                  <input type="hidden" name="action" value="authenticate">
                  <input type="text" name="login" placeholder="login"/>
                  <input type="password" name="mdp" placeholder="mot de passe"/>
                  <button>login</button>
              </form>
          </div>
        </div>';
    } else {
        echo ' <div class="login-page">
           <div class="form">
               <p>Nombre de tentatives trop important ! Vous ne pouvez plus vous connecter</p>
           </div>
         </div>';
    }
    if (isset($_REQUEST["nullvalue"])) {
        echo '<p class="errmsg">Merci de saisir votre login et votre mot de passe</p>';
    } else if (isset($_REQUEST["badvalue"]) && (isset($_SESSION['failed_authentification']) && $_SESSION[failed_authentification] < 3)) {
        echo '<p class="errmsg">Votre login/mot de passe est incorrect</p>';
      } else if (isset($_REQUEST["disconnect"])) {
        echo '<p>Vous avez bien été déconnecté.</p>';
      }
    ?>
  </section>

</body>
</html>
