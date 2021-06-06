<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="yandex-verification" content="abc11bf9124776d9" />

  <!-- plugins css -->
  <link rel="stylesheet" href="/css/plugins/swiper.min.css">
  <link rel="stylesheet" href="/css/plugins/advanced-cropper.css">
  <link rel="stylesheet" href="/css/plugins/vue-js-modal.css">
  
  <!-- common css -->
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/adaptation.css">

  <!-- favicon -->
  <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/png">

  <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-KWGCRFR');</script>
  <!-- End Google Tag Manager -->

  <title>FlamingoArt</title>

</head>

<body>

  <div id="app">

    <app-header></app-header>
    
    <div class="main">
      
      <router-view :key="($route.name == 'chat') ? 'chat' : $route.fullPath"></router-view>

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

  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KWGCRFR"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

  @isset ($userInfo)

  <script>
    window.USER = @json($userInfo);
  </script>

  @endisset

  @isset ($message)

  <script>
    window.MESSAGE = @json($message);
  </script>

  @endisset

  <script src="/js/app.js"></script>
  
</body>
</html>