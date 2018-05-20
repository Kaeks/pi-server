<?php
  require_once 'shared/logic.php';

  if (isset($_POST["registerbtn"])) {
    $msg = $unamemsg = $pwordmsg = [];
    $username = $db->escape_string($_POST["username"]);
    $password = $db->escape_string($_POST["password"]);
    $passwordval = $db->escape_string($_POST["passwordval"]);

    $unamemsg = $db->unameRequirements($username);
    $pwordmsg = $db->pwordRequirements($password);

    if ($password != $passwordval) {
      array_push($msg, "Passwords must match");
    } else if (empty($unamemsg) and empty($pwordmsg)) {
      if ($db->createNewUser($username, $password)) {
        if ($db->login($username, $password) !== true) {
          $msg = $db->login($username, $password);
        }
      } else {
        array_push($msg, "There was an error creating an account.");
      }
    } else {
      $msg = array_merge($unamemsg, $pwordmsg);
    }
  }
?>
<!doctype html>
<html>
<head>
  <?php require 'shared/head.php';?>
  <link rel='stylesheet' href='style/auth.css'/>
  <title>register - macipe</title>
</head>
<body>
  <?php require 'shared/header.php';?>
  <?php require 'shared/sidebar.php';?>
  <div id='content'>
    <div id='sheet'>
      <form id='auth' action='' method='post'>
        <h1>Register</h1>
        <input class='username' type='text' name='username' placeholder='Username'/>
        <input class='password' type='password' name='password' placeholder='Password'/>
        <input class='password' type='password' name='passwordval' placeholder='Repeat Password'/>
        <button class='authbtn' type='submit' name='registerbtn'>register</button>
        <div id='info-msg'>
          <?php
          if (!empty($msg)) {
            foreach ($msg as $val) {
              echo "<span>$val</span>";
            }
          }
          ?>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
