<div id='sidebar'>
  <a class='sidebar-elem' href='index' <?= getActive('index') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('home');?>'/></svg>
    <span>home</span>
  </a>
  <a class='sidebar-elem' href='newrecipe' <?= getActive('newrecipe') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('newrecipe');?>'/></svg>
    <span>new recipe</span>
  </a>
</div>
<div id='navbar'>
  navbar
</div>
