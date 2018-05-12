<!doctype html>
<html>
<style>
  html, body {
    width: 100vw;
    height: 100vh;
    margin: 0;
    padding: 0;
    font-family: 'Lato';
    font-size: 20px;
  }

  body {
    display: grid;
    grid-template-columns: auto;
    grid-template-rows: 8vh auto;
  }

  #header {
    position: fixed;
    top: 0;
    left: 0;
    overflow: hidden;
    width: 100vw;
    height: 8vh;
    padding: 0 10px;
    display: flex;
    align-items: center;
    grid-area: 1 / 1 / 1 / 1;
    color: #FFF;
    background: #FFA726;
    border-bottom: 5px solid #FB8C00;
  }

  #content {
    grid-area: 2 / 1 / 2 / 1;
  }
</style>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"/>
  <link rel="stylesheet" href="style/general.css"/>
  <link rel="icon" href="assets/icon.png"/>
  <title>Pi Webserver Project</title>
</head>
<body>
  <div id='header'>
    <h1>pi</h1>
  </div>
  <div id='content'>
  </div>
</body>
</html>
