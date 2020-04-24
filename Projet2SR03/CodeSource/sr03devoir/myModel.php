<?php

function getMySqliConnection() {
  $db_connection_array = parse_ini_file("config/config.ini");
  return new mysqli($db_connection_array['DB_HOST'], $db_connection_array['DB_USER'], $db_connection_array['DB_PASSWD'], $db_connection_array['DB_NAME']);
}

function findUserByLoginPwd($login, $pwd) {
  $mysqli = getMySqliConnection();

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
      $utilisateur = false;
  } else {
      $req=$mysqli->prepare("select nom,prenom,login,id_user,numero_compte,profil_user,solde_compte from users where login=? and mot_de_passe=?");
      $req->bind_param('ss', $login, $pwd);
     if (! $req->execute()) {
         echo 'Erreur requête BDD ['.$req.'] (' . $mysqli->errno . ') '. $mysqli->error;
         $utilisateur = false;
     } else {
         $result = $req->get_result();
         $row = $result->fetch_assoc();
         if (empty($row)) {
           $utilisateur = false;
         } else {
           $utilisateur= $row;
         }
     }
      $mysqli->close();
  }

  return $utilisateur;
}


function findAllUsers() {
  $mysqli = getMySqliConnection();

  $listeUsers = array();

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } else {
      $req="select nom,prenom,login,id_user,profil_user,numero_compte,solde_compte from users";
      if (!$result = $mysqli->query($req)) {
          echo 'Erreur requête BDD ['.$req.'] (' . $mysqli->errno . ') '. $mysqli->error;
      } else {
          while ($unUser = $result->fetch_assoc()) {
            $listeUsers[$unUser['id_user']] = $unUser;
          }
          $result->free();
      }
      $mysqli->close();
  }

  return $listeUsers;
}


function transfert($dest, $src, $mt) {
  $mysqli = getMySqliConnection();

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
      $utilisateur = false;
  } else {
      $req=$mysqli->prepare("update users set solde_compte=solde_compte+? where numero_compte=?");
      $req->bind_param('ss', $mt, $dest);
     if (! $req->execute()) {
         echo 'Erreur requête BDD ['.$req.'] (' . $mysqli->errno . ') '. $mysqli->error;
     }
     $req=$mysqli->prepare("update users set solde_compte=solde_compte-? where numero_compte=?");
     $req->bind_param('ss', $mt, $src);
    if (! $req->execute()) {
        echo 'Erreur requête BDD ['.$req.'] (' . $mysqli->errno . ') '. $mysqli->error;
    }
      $mysqli->close();
  }

  return $utilisateur;
}


function findMessagesInbox($userid) {
  $mysqli = getMySqliConnection();

  $listeMessages = array();

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } else {
      $req="select id_msg,sujet_msg,corps_msg,u.nom,u.prenom from messages m, users u where m.id_user_from=u.id_user and id_user_to=".$userid;
      if (!$result = $mysqli->query($req)) {
          echo 'Erreur requête BDD ['.$req.'] (' . $mysqli->errno . ') '. $mysqli->error;
      } else {
          while ($unMessage = $result->fetch_assoc()) {
            $listeMessages[$unMessage['id_msg']] = $unMessage;
          }
          $result->free();
      }
      $mysqli->close();
  }

  return $listeMessages;
}


function addMessage($to,$from,$subject,$body) {
    $mysqli = getMySqliConnection();
    $body = htmlspecialchars($body);
    if ($mysqli->connect_error) {
        echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
    } else {
        $req = "insert into messages(id_user_to,id_user_from,sujet_msg,corps_msg) values ($to,$from,'$subject','$body')";
        if (!$result = $mysqli->query($req)) {
            echo 'Erreur requête BDD ['.$req.'] (' . $mysqli->errno . ') '. $mysqli->error;
        }
    $mysqli->close();
  }
}

?>
