<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- common css -->
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/adaptation.css">

  <!-- plugins css -->
  <link rel="stylesheet" href="/css/plugins/swiper.min.css">

  <!-- favicon -->
  <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/png">

  <title>FlamingoArt</title>
</head>
<body>
  <div id="app">
    <app-header></app-header>
    <div class="main">
      
      <router-view :key="$route.path"></router-view>

        <transition name="fade">
          <div 
            v-if="cookieAgreementShow"
            class="cookie-agreement"
          >
            <span class="cookie-agreement__text">Мы используем файлы cookies, чтобы вам было удобнее пользоваться сайтом. Оставаясь на сайте, вы соглашаетесь с этой технологией и <a href="#"><u>политикой конфиденциальности</u></a></span>
            <div class="cookie-agreement__buttons">
              <button class="btn" @click="cookieAgreementClose">Ок</button>
              <button class="btn btn_no-bg">Подробнее</button>
            </div>
          </div>
      </transition>

      
      <notification
        ref="notification"
        :data="notification"
      ></notification>
      

    </div>
    {{-- <app-footer></app-footer> --}}
  </div>

  @isset ($userInfo)

  <script>
    window.USER = @json($userInfo);
  </script>

  @endisset

  <script src="/js/app.js"></script>
  
</body>
</html>