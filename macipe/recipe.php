<?php
  require_once 'shared/logic.php';
  if (isset($_GET['id'])) {
    $recipeid = $db->escape_string($_GET['id']);
    echo $recipeid;
  } else {
    header("Location: index");
  }

  $getRecipe = $db->query("SELECT R.userid, R.name, R.imgpath, U.username FROM recipe R INNER JOIN user U ON R.userid = U.userid WHERE R.recipeid = $recipeid");
  $row = $getRecipe->fetch_assoc();
  print_r($row);
  echo $getRecipe->num_rows;
?>
<!doctype html>
<html>
<head>
  <?php require 'shared/head.php';?>
  <link rel='stylesheet' href='style/recipe.css'/>
  <script src="script/newrecipe.js"></script>
  <title>new recipe - macipe</title>
</head>
<body>
  <?php require 'shared/header.php';?>
  <?php require 'shared/sidebar.php';?>
  <div id='content'>
    <div id='sheet'>
      <div id='upload-img'>
        <div id='img-preview'></div>
      </div>
      <div id='ingreds'>
        <h1>Ingredients</h1>
      </div>
      <div id='info'>
      </div>
      <div id='preparation'>
        <h1>Preparation</h1>
      </div>
    </div>
  </div>
</body>
</body>
</html>
