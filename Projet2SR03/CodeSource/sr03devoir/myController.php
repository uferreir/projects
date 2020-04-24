<?php
  require_once('myModel.php');
  session_start();

  // URL de redirection par défaut (si pas d'action ou action non reconnue)
  $url_redirect = "index.php";

  if (isset($_REQUEST['action'])) {

      if ($_REQUEST['action'] == 'authenticate') {
          /* ======== AUTHENT ======== */
          if (!isset($_REQUEST['login']) || !isset($_REQUEST['mdp']) || $_REQUEST['login'] == "" || $_REQUEST['mdp'] == "") {
              // manque login ou mot de passe
              $url_redirect = "connexion.php?nullvalue";

          } else {
              $utilisateur = findUserByLoginPwd($_REQUEST['login'], sha1($_REQUEST['mdp']));
              if ($utilisateur == false) {
                  if (!isset($_SESSION['failed_authentification'])){
                      $_SESSION['failed_authentification'] = 1;
                  } else {
                      $_SESSION['failed_authentification']++;
                  }

                // echec authentification
                $url_redirect = "connexion.php?badvalue";

              } else {
                // authentification réussie
                $_SESSION["connected_user"] = $utilisateur;
                $_SESSION["listeUsers"] = findAllUsers();
                unset($_SESSION["failed_authentification"]);
                $url_redirect = "accueil.php";
              }
          }

      } else if ($_REQUEST['action'] == 'disconnect') {
          /* ======== DISCONNECT ======== */
          unset($_SESSION["connected_user"]);
          unset($_SESSION["timestamp"]);
          unset($_SESSION["failed_authentification"]);
          //session_destroy();
          $url_redirect = '/sr03devoir/connexion.php';

      } else if ($_REQUEST['action'] == 'transfert') {
          /* ======== TRANSFERT ======== */
          if (is_numeric ($_REQUEST['montant']) && $_REQUEST['montant'] > 0) {
              transfert($_REQUEST['destination'],$_SESSION["num_compte_source"], $_REQUEST['montant']);
              if ($_SESSION["connected_user"]["numero_compte"] == $_SESSION["num_compte_source"]) {
                  $_SESSION["connected_user"]["solde_compte"] = $_SESSION["connected_user"]["solde_compte"] -  $_REQUEST['montant'];
              } else if ( $_SESSION["connected_user"]["numero_compte"] == $_REQUEST['destination']) {
                   $_SESSION["connected_user"]["solde_compte"] = $_SESSION["connected_user"]["solde_compte"] + $_REQUEST['montant'];
              }

              $url_redirect = "virement.php?trf_ok";

          } else {
              $url_redirect = "virement.php?bad_mt=".$_REQUEST['montant'];
          }
      } else if ($_REQUEST['action'] == 'sendmsg') {
          /* ======== MESSAGE ======== */
          addMessage($_REQUEST['to'],$_SESSION["connected_user"]["id_user"],$_REQUEST['sujet'],$_REQUEST['corps']);
          $url_redirect = "messagerie.php?msg_ok";

      } else if ($_REQUEST['action'] == 'msglist') {
          /* ======== MESSAGE ======== */
          if ($_REQUEST["userid"] == $_SESSION["connected_user"]["id_user"] ) {
             $_SESSION['messagesRecus'] = findMessagesInbox($_REQUEST["userid"]);
             $_SESSION['passageParController'] = true;
             $url_redirect = "messagerie.php";
         } else {
             $url_redirect = "accueil.php";
         }


     } else if ($_REQUEST['action'] == 'fichesclients') {
         $_SESSION["listeUsers"] = findAllUsers();
         $_SESSION['passageParController'] = true;
         $url_redirect = "fichesclients.php";
     }


  }

  header("Location: $url_redirect");

?>
