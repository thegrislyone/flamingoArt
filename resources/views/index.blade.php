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

  <!-- Yandex.Metrika counter -->
  <script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]function(){(m[i].a=m[i].a[]).push(arguments)};
    m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(76615209, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        ecommerce:"dataLayer"
    });
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/76615209" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
  <!-- /Yandex.Metrika counter -->

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

  {{-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script> --}}

  @isset ($userInfo)

  <script>
    window.USER = @json($userInfo);
  </script>

  @endisset

  <script src="/js/app.js"></script>
  
</body>
</html>