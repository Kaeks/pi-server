<?php
$db = new DatabaseConnection();

function getSVG($svg) {
  $svg_list = [
    'home' => 'M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z',
    'search' => 'M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z'
  ];
  return $svg_list[$svg];
}

function getActive($link) {
  if ($link == 'index') {
    return (strpos($_SERVER['REQUEST_URI'], '/index') !== false or $_SERVER['REQUEST_URI'] == '' or $_SERVER['REQUEST_URI'] == '/');
  } else {
    return (strpos($_SERVER['REQUEST_URI'], "/$link") !== false);
  }
}

// Returns a random string (0-9;a-Z;!;$;.) with length $length
function randomString($length) {
  $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
  $c_length = strlen($characters);
  $string = "";
  for ($i = 0; $i < $length; $i++) {
    $string .= $characters[rand(0, $c_length - 1)];
  }
  return $string;
}

class DatabaseConnection extends mysqli {
  // Constructor, this gets called every time a new instance of DatabaseConnection is created
  function __construct() {
    require 'credentials.php';
    $instance = @parent::__construct($db_servername, $db_username, $db_password, $db_name);

    if ($this->connect_error) {
      die($this->connect_errno . $this->connect_error);
    }

  }

  function newRecipe($userid, $name, $tags, $ingredients, $preparation) {
    $name = @parent::escape_string($name);

    $ident = randomString(16);
    while (!self::checkRecipeIdent($ident)) {
      $ident = randomString(16);
    }

    echo 'Add Recipe:<br>';
    $addRecipe = @parent::query("INSERT INTO recipe (userid, name, identifier) VALUES ('$userid', '$name', '$ident')");

    foreach ($tags as $tag) {
      $tag = @parent::escape_string($tag);
      $addTag = @parent::query("INSERT INTO tag (recipeid, tag) VALUES ((SELECT recipeid FROM recipe WHERE identifier = '$ident'), '$tag')");
    }

    foreach ($ingredients as $place => $ingred) {
      $name = @parent::escape_string($ingred['name']);
      $amount = @parent::escape_string($ingred['amount']);
      $unit = @parent::escape_string($ingred['unit']);
      $addIngred = @parent::query("INSERT INTO ingredient (recipeid, place, name, amount, unit) VALUES ((SELECT recipeid FROM recipe WHERE identifier = '$ident'), '$place', '$name', '$amount', '$unit')");
    }

    foreach ($preparation as $step => $desc) {
      $desc = @parent::escape_string($desc);
      $addPrep = @parent::query("INSERT INTO preparation (recipeid, step, description) VALUES ((SELECT recipeid FROM recipe WHERE identifier = '$ident'), '$step', '$desc')");
    }
  }

  function displayPreview($mysqli_query) {
  if ($mysqli_query) {
    $return = '';
    while ($row_recipe = $mysqli_query->fetch_assoc()) {
      $recipeid = $row_recipe['recipeid'];
      $title = htmlspecialchars($row_recipe['name']);
      $img = isset($row_recipe['imgpath']) ? $row_recipe['imgpath'] : 'assets/no_img.png';

      $return_tags = '';

      $getTags = @parent::query("SELECT tag FROM tag WHERE recipeid = $recipeid");
      if ($getTags) {
        while ($row_tag = $getTags->fetch_assoc()) {
          $tag = htmlspecialchars($row_tag['tag']);
          $return_tags .= "<span class='preview-tag'>$tag</span>";
        }
      }

      $return .= <<<RETURN
      <a href='recipe?id=$recipeid'>
      <div class='grid-item' style='background-image: url("$img")'>
          <div class='preview-title'>
            $title
          </div>
          <div class='preview-tags'>
            $return_tags
          </div>
        </div>
        </a>
RETURN;
      }
      return $return;
    }
  }

  function getCurUser() {
    require('credentials.php');
    if (isset($_COOKIE['identifier'])) {
      return self::getUserID($_COOKIE['identifier']);
    } else {
      return false;
    }
  }

  function getUserID($identifier) {
    return @parent::query("SELECT userid FROM user WHERE identifier = '$identifier'")->fetch_assoc()['userid'];
  }

  function getUsername($userid) {
    return @parent::query("SELECT username FROM user WHERE userid = '$userid'")->fetch_assoc()['username'];
  }

  function checkSelf($userid) {
    return ($userid == self::getCurUser()) ? true : false;
  }

  function createIdentifier($userid) {
    $identifier = randomString(32);
    $checkExist = @parent::query("SELECT identifier FROM user WHERE identifier = '$identifier'");
    $valid = true;
    while ($row = $checkExist->fetch_assoc()) {
      if ($identifier == $row['identifier']) {
        $valid = false;
      }
    }
    if ($valid) { //identifier doesn't exist yet
      $createIdentifier = @parent::query("UPDATE user SET identifier = '$identifier' WHERE userid = '$userid'");
    } else { // identifier already exists
      self::createIdentifier();
    }
  }

  function checkRecipeIdent($identifier) {
    $checkExist = @parent::query("SELECT identifier FROM recipe WHERE identifier = '$identifier'");
    $valid = true;
    while ($row = $checkExist->fetch_assoc()) {
      if ($identifier == $row['identifier']) {
        $valid = false;
      }
    }
    return $valid;
  }

  function getIdentifier($userid) {
    require('credentials.php');
    $getIdentifier = @parent::query("SELECT identifier FROM user WHERE userid = '$userid'");
    $row = $getIdentifier->fetch_assoc();
    if ($row['IDENTIFIER']) {
      return $row['IDENTIFIER'];
    } else { // user doesn't have identifier yet
      self::createIdentifier($userid);
      self::getIdentifier($userid);
    }
  }

  function deleteAuthCookies() {
    foreach ($_COOKIE as $key => $val) {
      if ($key != 'theme' and $key != 'color' and $key != 'color-hacker') {
        setcookie($key, '', 1);
      }
    }
  }

  function auth() {
    require('credentials.php');
    if (isset($_COOKIE['identifier']) and isset($_COOKIE['hashed_password'])) {
      $identifier = $_COOKIE['identifier'];
      $hashed_password = $_COOKIE['hashed_password'];
      $getDBpword = @parent::query("SELECT PASSWORD FROM user WHERE IDENTIFIER = '$identifier'");
      $row = $getDBpword->fetch_assoc();
      $DBpword = $row['PASSWORD'];
      if ($hashed_password == $DBpword) {
        return true;
      }
    }
    return false;
  }
}

?>
