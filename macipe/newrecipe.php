<?php
  require_once 'shared/logic.php';

  if (isset($_POST['submit'])) {
    $userid = $db->getCurUser() ? $db->getCurUser() : 1;
    $recipename = $db->escape_string($_POST['recipename']);
    print_r($_POST);

    $tags = [];
    $ingredients = [];
    $preparation = [];

    $i = 0;
    $j = 0;

    foreach ($_POST as $key => $val) {
      if (strpos($key, 'tag') !== false) {
       $tag = $db->escape_string($_POST[$key]);
       array_push($tags, $val);
     } elseif (strpos($key, 'amt') !== false) {
        $num = substr($key, 3);
        $amt = $db->escape_string($_POST['amt' . $num]);
        $unit = $db->escape_string($_POST['unit' . $num]);
        $name = $db->escape_string($_POST['name' . $num]);
        $ingredients[$i] = array(
          'amt' => $amt,
          'unit' => $unit,
          'name' => $name
        );
        $i++;
      } elseif (strpos($key, 'desc') !== false) {
        $desc = $db->escape_string($_POST[$key]);
        $preparation[$j+1] = $desc;
        $j++;
      }
    }
    $db->newRecipe($userid, $recipename, $tags, $ingredients, $preparation);
    header('Location: index');
  }

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
        <input type='submit' name='submit'/>
    </form>
  </div>
</body>
</html>
