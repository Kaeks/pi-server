<?php
$db = new DatabaseConnection();

function getSVG($svg) {
  $svg_list = [
    'home' => 'M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z',
    'newrecipe' => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z',
    'search' => 'M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z',
    'confirm' => 'M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z',
    'tag' => 'M5.5,7A1.5,1.5 0 0,1 4,5.5A1.5,1.5 0 0,1 5.5,4A1.5,1.5 0 0,1 7,5.5A1.5,1.5 0 0,1 5.5,7M21.41,11.58L12.41,2.58C12.05,2.22 11.55,2 11,2H4C2.89,2 2,2.89 2,4V11C2,11.55 2.22,12.05 2.59,12.41L11.58,21.41C11.95,21.77 12.45,22 13,22C13.55,22 14.05,21.77 14.41,21.41L21.41,14.41C21.78,14.05 22,13.55 22,13C22,12.44 21.77,11.94 21.41,11.58Z'
  ];
  return $svg_list[$svg];
}

function getActive($link) {
  $uri = $_SERVER['REQUEST_URI'];
  if ($link == 'index') {
    return (
      strpos($uri, '/index') !== false
      or
      $uri == ''
      or
      $uri == '/'
      or
      substr($uri, -strlen('macipe/')) == 'macipe/'
      or
      substr($uri, -strlen('macipe')) == 'macipe'
    );
  } else {
    return (strpos($_SERVER['REQUEST_URI'], "/$link") !== false);
  }
}

function parseUnit($unit) {
  return $unit == '<none>' ? '' : $unit;
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

  function newRecipe($userid, $name, $tags, $ingredients, $preparation, $imgfile = null) {
    $name = @parent::escape_string($name);

    $ident = randomString(16);
    while (!self::checkRecipeIdent($ident)) {
      $ident = randomString(16);
    }

    $addRecipe = @parent::query("INSERT INTO recipe (userid, name, identifier) VALUES ('$userid', '$name', '$ident')");

    $getRecipeId = @parent::query("SELECT recipeid FROM recipe WHERE identifier = '$ident'");
    $recipeid = $getRecipeId->fetch_assoc()['recipeid'];

    foreach ($tags as $tag) {
      $tag = @parent::escape_string($tag);
      $addTag = @parent::query("INSERT INTO tag (recipeid, tag) VALUES ('$recipeid', '$tag')");
    }

    foreach ($ingredients as $place => $ingred) {
      echo $amount = @parent::escape_string($ingred['amt']);
      echo $unit = @parent::escape_string($ingred['unit']);
      echo $name = @parent::escape_string($ingred['name']);
      $addIngred = @parent::query("INSERT INTO ingredient (recipeid, place, name, amount, unit) VALUES ('$recipeid', '$place', '$name', '$amount', '$unit')");
    }

    foreach ($preparation as $step => $desc) {
      $desc = @parent::escape_string($desc);
      $addPrep = @parent::query("INSERT INTO preparation (recipeid, step, description) VALUES ('$recipeid', '$step', '$desc')");
    }

    if (!is_null($imgfile)) {
      self::updateImg($recipeid, $imgfile);
    }
  }

  function updateImg($recipeid, $imgfile) {
    $getIdent = @parent::query("SELECT identifier FROM recipe WHERE recipeid = $recipeid");
    $ident = $getIdent->fetch_assoc()['identifier'];

    $info = pathinfo($imgfile['name']);
    $ext = $info['extension'];
    $filename = $ident . '.' . $ext;
    $targetdir = 'assets/imgs/';
    $target = $targetdir . $filename;

    // Check if recipe has an image associated to it already and deletes the image in case it has one.
    $getImgPath = @parent::query("SELECT imgpath FROM recipe WHERE recipeid = $recipeid");
    $db_imgpath = $getImgPath->fetch_assoc()['imgpath'];
    if ($db_imgpath != 'assets/no_img.png' and file_exists($db_imgpath)) {
      unlink($db_imgpath);
    }

    // Move the $imgfile to $target
    if (move_uploaded_file($imgfile['tmp_name'], $target)) {
      $setImgPath = @parent::query("UPDATE recipe SET imgpath = '$target' WHERE recipeid = $recipeid");
      if ($setImgPath) {
        return true;
      } else {
        echo 'Could not save path in database.';
        return false;
      }
    } else {
      echo 'Could not save uploaded file.';
      return false;
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
      $getPrep = @parent::query("SELECT description FROM preparation WHERE recipeid = $recipeid");
      $len = 0;
      $prep = '';
      while ($prep_r = $getPrep->fetch_assoc()['description']) {
        if ($len != 0) {
          $prep .= '<br>';
        }
        $prep .= $prep_r;
        $len += strlen($prep_r);
        if ($len >= 300) {
          break;
        }
      }

      $svgtag = getSVG('tag');

      $return .= <<<RETURN
      <a class='item' href='recipe?id=$recipeid'>
        <div class='preview-img' style='background-image: url("$img")'></div>
        <div class='preview-title'>
          $title
        </div>
        <div class='preview-desc'>
          <div class='preview-tags'>
            <svg viewBox='0 0 24 24'>
              <path d='$svgtag'/>
            </svg>
            $return_tags
          </div>
          <div class='preview-prep'>
            $prep...
          </div>
          <div class='fade'></div>
        </div>
      </a>
RETURN;
      }
      return $return;
    } else {
      echo 'query err';
    }
  }

  function deleteRecipe($recipeid) {
    return @parent::query("DELETE FROM recipe WHERE recipeid = $recipeid");
  }

  function cleanupRecipes() {
    $deleted = array(
      'tag' => 0,
      'ingred' => 0,
      'prep' => 0
    );
    $getTags = @parent::query("SELECT recipeid FROM tag");
    while ($row_tag = $getTags->fetch_assoc()) {
      $recipeid = $row_tag['recipeid'];
      $getRecipe = @parent::query("SELECT recipeid FROM recipe WHERE recipeid = $recipeid");
      if ($getRecipe->num_rows == 0) {
        $deleteTags = @parent::query("DELETE FROM tag WHERE recipeid = $recipeid");
        $deleted['tag']++;
      }
    }

    $getIngreds = @parent::query("SELECT recipeid FROM ingredient");
    while ($row_ingred = $getIngreds->fetch_assoc()) {
      $recipeid = $row_ingred['recipeid'];
      $getRecipe = @parent::query("SELECT recipeid FROM recipe WHERE recipeid = $recipeid");
      if ($getRecipe->num_rows == 0) {
        $deleteIngreds = @parent::query("DELETE FROM ingredient WHERE recipeid = $recipeid");
        $deleted['ingred']++;
      }
    }

    $getPrep = @parent::query("SELECT recipeid FROM preparation");
    while ($row_prep = $getPrep->fetch_assoc()) {
      $recipeid = $row_prep['recipeid'];
      $getRecipe = @parent::query("SELECT recipeid FROM recipe WHERE recipeid = $recipeid");
      if ($getRecipe->num_rows == 0) {
        $deletePrep = @parent::query("DELETE FROM preparation WHERE recipeid = $recipeid");
        $deleted['prep']++;
      }
    }
    return $deleted;
  }

  function getCurUserID() {
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
    return ($userid == self::getCurUserID()) ? true : false;
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
      echo 'is valid';
      $createIdentifier = @parent::query("UPDATE user SET identifier = '$identifier' WHERE userid = '$userid'");
      if ($createIdentifier) {
        echo 'yup';
      } else {
        echo 'nah';
      }
    } else { // identifier already exists
      echo 'exists already';
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
      echo $row['IDENTIFIER'];
      return $row['IDENTIFIER'];
    } else { // user doesn't have identifier yet
      echo 'doesnt have yet';
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

  function unameRequirements($username) {
    require('credentials.php');
    $msg = [];
    if (strlen($username) == 0 or ctype_space($username) or $username == '') {
      array_push($msg, "Username cannot be empty or only whitespace.");
    }
    if (strlen($username) > 20) {
      array_push($msg, "Username must be at most 20 characters long.");
    }
    if (!preg_match('/^[a-zA-Z0-9_]*$/', $username)) {
      array_push($msg, "Username can only contain alphanumeric characters (a-z, 0-9, _).");
    }
    if ($username != self::getUsername(self::getCurUserID())) {
      $checkExist = @parent::query("SELECT username FROM user WHERE UPPER(username) = UPPER('$username')");
      if ($checkExist) {
        if ($checkExist->num_rows > 0) {
          array_push($msg, "Username already exists.");
        }
      }
    }
    return $msg;
  }

  function pwordRequirements($password) {
    $msg = [];
    if (strlen($password) < 6) {
      array_push($msg, "Password must be at least 6 characters long.");
    }
    if (!(preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password))) {
      array_push($msg, "Password must contain at least one lowercase <b>and</b> one uppercase character.");
    }
    if (!(preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password))) {
      array_push($msg, "Password must contain at least one letter <i>(a-z)</i> <b>and</b> one number <i>(0-9)</i>.");
    }
    return $msg;
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

  function createNewUser($username, $password) {
    $username = @parent::escape_string($username);
    $password = @parent::escape_string($password);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if ($createUser = @parent::query("INSERT INTO user (username, password) VALUES ('$username','$hashed_password')")) {
      $userid = @parent::query("SELECT userid FROM user WHERE username = '$username'")->fetch_assoc()['userid'];
      self::createIdentifier($userid);
      return true;
    } else {
      return false;
    }
  }

  function login($username, $password) {
    $msg = [];
    if ($getInfo = @parent::query("SELECT identifier, password FROM user WHERE USERNAME = '$username'")) {
      $row = $getInfo->fetch_assoc();
      $dbPassword = $row['password'];
      $identifier = $row['identifier'];
      if (password_verify($password, $dbPassword)) {
        //pass
        setcookie('identifier', $identifier);
        setcookie('hashed_password', $dbPassword);
        header("Location: index");
        return true;
      } else {
        //invalid
        array_push($msg, "Invalid password or username");
      }
    } else {
      //query error
      array_push($msg, "Query error");
    }
    return $msg;
  }

}

?>
