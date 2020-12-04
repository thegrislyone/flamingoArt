<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FlamingoArt</title>
</head>
<body>
  <div id="app">
    <app-header></app-header>
    <router-view :key="$route.path"></router-view>
    <app-footer></app-footer>
  </div>

  <script src="./js/app.js"></script>
</body>
</html>