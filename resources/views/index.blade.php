<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/plugins/swiper.min.css">
  <title>FlamingoArt</title>
</head>
<body>
  <div id="app">
    <app-header></app-header>
    <div class="main">
      <router-view :key="$route.path"></router-view>
    </div>
    <app-footer></app-footer>
  </div>

  <script src="./js/app.js"></script>
</body>
</html>