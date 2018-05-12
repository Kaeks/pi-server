<?php
  require_once 'shared/logic.php';
?>
<!doctype html>
<html>
<head>
  <?php require 'shared/head.php';?>
  <link rel='stylesheet' href='style/newrecipe.css'/>
  <script src="script/newrecipe.js"></script>
  <title>new recipe - macipe</title>
</head>
<body>
  <?php require 'shared/header.php';?>
  <?php require 'shared/sidebar.php';?>
  <div id='content'>
    <form id='sheet' action='' method='post'>
        <div id='imgframe'>
          <img src='../../muffin.jpg'/>
        </div>
        <div id='ingreds'>
          <h1>Ingredients</h1>
          <div id='ingred-inputs'>
            <div id='ingred0'>
              <input class='amt' name='amt0' type='text' placeholder='Amount'/>
              <input class='unit' name='unit0' type='text' placeholder='Unit'/>
              <input class='name' name='name0' type='text' placeholder='Ingredient'/>
            </div>
          </div>
          <button type='button' id='add-ingredient' onclick='addIngredient()'>+</button>
        </div>
        <div id='info'>
          <input id='recipename' name='recipename' placeholder='Name'/>
          <div id='tag-inputs'>
            <div id='tag0'>
              <input class='tag' name='tag0' type='text' placeholder='Tag'/>
            </div>
          </div>
          <button type='button' id='add-tag' onclick='addTag()'>+</button>
        </div>
        <div id='preparation'>
          <h1>Preparation</h1>
          <div id='prep-inputs'>
            <div id='prep0' class='prepstep'>
              <textarea class='prepdesc' name='desc0' placeholder='Description'></textarea>
            </div>
          </div>
          <button type='button' id='add-desc' onclick='addDesc()'>+</button>
        </div>
    </form>
  </div>
</body>
</html>
