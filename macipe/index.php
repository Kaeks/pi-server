<?php
  require_once 'shared/logic.php';
?>
<!doctype html>
<html>
<head>
  <?php require 'shared/head.php';?>
  <link rel='stylesheet' href='style/index.css'/>
  <title>home - macipe</title>
</head>
<body>
  <?php require 'shared/header.php';?>
  <?php require 'shared/sidebar.php';?>
  <div id='content'>
    <div id='sheet'>
      <form class='search-bar' action="search" method="get">
        <input type="text" name="q" placeholder="Search" <?= isset($_GET['q']) ? 'value=\'' . htmlspecialchars($_GET['q']) . '\'' : ''?> autocomplete="off" required/>
        <button type="submit">
          <svg viewBox="0 0 24 24">
            <path d='<?= getSVG('search');?>'/>
          </svg>
        </button>
      </form>
      <div class='grid-btn'>
        starter
      </div>
      <div class='grid-btn'>
        main course
      </div>
      <div class='grid-btn'>
        dessert
      </div>
      <div id='recipe-previews'>
        <?= $db->displayPreview($db->query("SELECT recipeid, name, imgpath FROM recipe R ORDER BY RAND() LIMIT 4"));?>
      </div>
    </div>
  </div>
</body>
</html>
