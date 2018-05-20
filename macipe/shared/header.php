<div id='header'>
  <span id='macipe-logo'><a href='../macipe'>macipe</a></span>
  <div>
    <div id='btn-holder'>
      <?php if ($db->auth()):?>
      <span id='logged-as'>Welcome, <a href='profile?id=<?= $db->getCurUserID()?>'><?= $db->getUsername($db->getCurUserID())?></a>!</span>
      <a class='secondary-btn color-secondary' onclick='logout()'>log out</a>
      <?php else:?>
      <a class='secondary-btn color-secondary' href='login'>log in</a>
      <a class='primary-btn color-secondary' href='register'>register</a>
      <?php endif;?>
    </div>
    <form class='search-bar' action="search" method="get">
      <input type="text" name="q" placeholder="Search" <?= isset($_GET['q']) ? 'value="' . htmlspecialchars($_GET['q']) . '"' : ''?> autocomplete="off" required/>
      <button type="submit">
        <svg viewBox="0 0 24 24">
          <path d='<?= getSVG('search');?>'/>
        </svg>
      </button>
    </form>
  </div>
</div>
