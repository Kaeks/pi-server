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
    <div id='sheet' class='grid'>
      <form class='search-bar' action="search" method="get">
        <input type="text" name="q" placeholder="Search" <?= isset($search) ? 'value="' . $search . '"' : ''?> autocomplete="off" required/>
        <button type="submit">
          <svg viewBox="0 0 24 24">
            <path d='<?= getSVG('search');?>'/>
          </svg>
        </button>
      </form>
      <?php if (isset($search)):
        $search = $db->escape_string($search);
        ?>
        <div id='recipe-previews'>
          <?= $db->displayPreview($db->query("SELECT DISTINCT R.recipeid, name, imgpath FROM recipe R INNER JOIN tag T ON R.recipeid = T.recipeid WHERE name LIKE '%$search%' OR T.tag LIKE '%$search%'"));?>
        </div>
      <?php else:?>
        <div id='recipe-previews'>
          <?= $db->displayPreview($db->query("SELECT recipeid, name, imgpath FROM recipe R ORDER BY RAND() LIMIT 4"));?>
        </div>
      <?php endif;?>
    </div>
  </div>
</body>
</html>
