<?php
  require_once 'shared/logic.php';
  if (isset($_GET['id'])) {
    $recipeid = $db->escape_string($_GET['id']);
  } else {
    header("Location: index");
    exit();
  }

  $getRecipe = $db->query("SELECT R.userid, R.name, R.imgpath, U.username FROM recipe R INNER JOIN user U ON R.userid = U.userid WHERE R.recipeid = $recipeid");
  $row = $getRecipe->fetch_assoc();
  $userid = $row['userid'];
  //$username = htmlspecialchars($row['userame']);
  $recipename = htmlspecialchars($row['name']);
  $imgpath = $row['imgpath'];
  //echo $getRecipe->num_rows . '<br>';

  $getTags = $db->query("SELECT tag FROM tag WHERE recipeid = $recipeid");
  $getIngreds = $db->query("SELECT amount, unit, name FROM ingredient WHERE recipeid = $recipeid ORDER BY place");
  $getPrep = $db->query("SELECT description FROM preparation WHERE recipeid = $recipeid ORDER BY step");

?>
<!doctype html>
<html>
<head>
  <?php require 'shared/head.php';?>
  <link rel='stylesheet' href='style/recipe.css'/>
  <script src="script/newrecipe.js"></script>
  <title><?= $recipename?> - macipe</title>
</head>
<body>
  <?php require 'shared/header.php';?>
  <?php require 'shared/sidebar.php';?>
  <div id='content'>
    <div id='sheet'>
      <div id='recipe-img' style='background-image: url("<?= $imgpath?>")'></div>
      <div id='info'>
        <span class='recipename'><?= $recipename?></span>
      </div>
      <hr>
      <div id='ingreds'>
        <h1>Ingredients</h1>
        <table id='ingredtable'>
          <?php
          $return = '';
          while ($row_i = $getIngreds->fetch_assoc()) {
            $amt = htmlspecialchars($row_i['amount']);
            $unit = htmlspecialchars(parseUnit($row_i['unit']));
            $name = htmlspecialchars($row_i['name']);
            $return .= <<<RETURN
            <tr>
              <td class='amt'>$amt $unit</td>
              <td class='name'>$name</td>
            </tr>
RETURN;
          }
          echo $return;
          ?>
        </table>
      </div>
      <div class='vr b'>
      </div>
      <div id='preparation'>
        <h1>Preparation</h1>
      </div>
    </div>
  </div>
</body>
</body>
</html>
