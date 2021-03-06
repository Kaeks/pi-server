<?php
  require_once 'shared/logic.php';

  if (isset($_POST["loginbtn"])) {
    $msg = [];
    $username = $db->escape_string($_POST['username']);
    $password = $db->escape_string($_POST['password']);
    if (!empty($username) && !empty($password)) {
      if ($db->login($username, $password) !== true) {
        $msg = $db->login($username, $password);
      }
    } else {
      array_push($msg, "Please enter your username and your password");
    }
  }

?>
<!doctype html>
<html>
<head>
  <?php require 'shared/head.php';?>
  <link rel='stylesheet' href='style/auth.css'/>
  <title>login - macipe</title>
</head>
<body>
  <?php require 'shared/header.php';?>
  <?php require 'shared/sidebar.php';?>
  <?php
  if ($db->auth()) {
    // already logged in
    // prompt to change user
    ?>
    <div id='overlay'>
      <div class='popup'>
        <p>You are already logged in.</p>
        <p>Do you want to log out?</p>
      </div>
    </div>
    <?php
  }
  ?>
  <div id='content'>
    <div id='sheet'>
      <form id='auth' action='' method='post'>
        <h1>Login</h1>
        <input class='username' type='text' name='username' placeholder='Username'/>
        <input class='password' type='password' name='password' placeholder='Password'/>
        <button class='authbtn' type='submit' name='loginbtn'>log in</button>
        <span class='no-acc'>
          No account yet?
          <a href='register'>
            Register here!
          </a>
        </span>
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
