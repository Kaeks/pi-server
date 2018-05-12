<?php
  require_once 'shared/logic.php';
?>
<!doctype html>
<html>
<head>
  <?php require 'shared/head.php';?>
  <title>home - macipe</title>
</head>
<body>
  <?php require 'shared/header.php';?>
  <?php require 'shared/sidebar.php';?>
  <div id='content'>
    content<br>
    <button onclick='document.getElementById("more").innerHTML += "more content<br>"'>More Content</button><br>
    <p id='more'>
    </p>
  </div>
</body>
</html>
