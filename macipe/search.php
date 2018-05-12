<?php
  require_once 'shared/logic.php';
  if (isset($_GET['q'])) {
    $search = htmlspecialchars($_GET['q']);
  }
?>
<!doctype html>
<html>
<head>
  <?php require 'shared/head.php';?>
  <link rel='stylesheet' href='style/search.css'/>
  <title>Search<?= isset($search) ? ": $search" : '' ?> - macipe</title>
</head>
<body>
  <?php require 'shared/header.php';?>
  <?php require 'shared/sidebar.php';?>
  <div id='content'>
    <div id='sheet'>
      <?php if (isset($search)):?>
        you seem to be looking for <i><?=$search?></i>
      <?php else:?>
        Search stuff!
      <?php endif;?>
    </div>
  </div>
</body>
</html>
