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

  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id=%27+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-MKPRBHR');</script>
  <!-- End Google Tag Manager -->

  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MKPRBHR"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

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