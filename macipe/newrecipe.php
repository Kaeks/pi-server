<?php
  require_once 'shared/logic.php';

  if (isset($_POST['submit']) and isset($_POST['recipename']) and isset($_POST['amt0']) and isset($_POST['unit0']) and isset($_POST['name0']) and isset($_POST['desc0'])) {
    $userid = $db->getCurUser() ? $db->getCurUser() : 1;
    $recipename = $db->escape_string($_POST['recipename']);

    $tags = $ingredients = $preparation = [];
    $i = $j = 0;

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

    if (isset($_FILES['thumb'])) {
      $imgfile = $_FILES['thumb'];
      $db->newRecipe($userid, $recipename, $tags, $ingredients, $preparation, $imgfile);
    } else {
      $db->newRecipe($userid, $recipename, $tags, $ingredients, $preparation);
    }
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
    <form id='sheet' action='' method='post' enctype="multipart/form-data">
      <div id='upload-img'>
        <div id='img-preview'></div>
        <div class='upload-btn-wrapper'>
          <button class='upload-btn' type='button'>Upload Image</button>
          <input type='file' name='thumb'/>
        </div>
      </div>
      <div id='ingreds'>
        <h1>Ingredients</h1>
        <div id='ingred-inputs'>
          <div id='ingred0'>
            <input class='amt' name='amt0' type='text' placeholder='Amount' required/>
            <input class='unit' name='unit0' type='text' placeholder='Unit' required/>
            <input class='name' name='name0' type='text' placeholder='Ingredient' required/>
          </div>
        </div>
        <button type='button' id='add-ingredient' class='add' onclick='addIngredient()'>+</button>
      </div>
      <div id='info'>
        <input class='recipename' name='recipename' type='text' placeholder='Name' required/>
        <div id='tag-inputs'>
          <div id='tag0'>
            <input class='tag' name='tag0' type='text' placeholder='Tag'/>
          </div>
        </div>
        <button type='button' id='add-tag' class='add' onclick='addTag()'>+</button>
      </div>
      <div id='preparation'>
        <h1>Preparation</h1>
        <div id='prep-inputs'>
          <div id='prep0' class='prepstep'>
            <textarea class='prepdesc' name='desc0' placeholder='Description' required></textarea>
          </div>
        </div>
        <button type='button' id='add-desc' class='add' onclick='addDesc()'>+</button>
      </div>
      <button class='floating-action-btn' type='submit' name='submit'>
        <svg viewBox="0 0 24 24">
          <path d='<?= getSVG('confirm');?>'/>
        </svg>
      </button>
    </form>
  </div>
</body>
<script>
if (window.FileReader) {
  document.querySelector('.upload-btn-wrapper > input[type="file"]').addEventListener('change', handleFileSelect, false);
} else {
  console.log('This browser does not support FileReader.');
}
</script>
</html>
