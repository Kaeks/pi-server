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

  a {
    text-decoration: none;
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

  #sheet {
    padding: 20px 0 100px 0;
    width: 50%;
    height: 100%;
    box-sizing: border-box;
    margin: 0 auto;
    display: grid;
    grid-template-rows: repeat(4, 1fr);
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 10px;
  }

  #sheet .grid-elem {
    width: 100%;
    height: 100%;
    background: #42A5F5;
    border-bottom: 5px solid #1976D2;
    border-right: 5px solid #1976D2;
    border-radius: 5px;
    box-sizing: border-box;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.2s;
    color: #FFF;
    font-size: 35px;
    font-weight: bold;
  }

  #sheet .grid-elem:hover {
    transform: translateY(-1px) translateX(-1px);
    border-bottom: 7px solid #1976D2;
    border-right: 7px solid #1976D2;
  }

  #sheet .grid-elem:active {
    border: 0px solid #1976D2;
    transform: translateY(2px) translateX(2px);
  }

</style>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"/>
  <link rel="icon" href="assets/icon.png"/>
  <title>Pi Webserver Project</title>
</head>
<body>
  <div id='header'>
    <h1>pi</h1>
  </div>
  <div id='content'>
    <div id='sheet'>
      <a href='macipe'><div class='grid-elem'>macipe</div></a>
      <div class='grid-elem'>more coming!</div>
    </div>
  </div>
</body>
</html>
