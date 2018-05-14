<div id='header'>
  <h1><a href='../macipe'>macipe</a></h1>
  <form class='search-bar' action="search" method="get">
    <input type="text" name="q" placeholder="Search" <?= isset($_GET['q']) ? 'value="' . htmlspecialchars($_GET['q']) . '"' : ''?> autocomplete="off" required/>
    <button type="submit">
      <svg viewBox="0 0 24 24">
        <path d='<?= getSVG('search');?>'/>
      </svg>
    </button>
  </form>
</div>
