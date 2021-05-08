<template>
  <div class="header-wrapper">

    <modal
      name="signForm"
      :classes="['modal','sign-in-form']"
      :height="'auto'"
      :scrollable="true"
      :min-width="320"
      :min-height="580"
      :adaptive="true"
      :max-width="480"
      :shiftY="0.1"
    >
      <sign-form
        :mode="formMode"
        @setMode="setFormMode"
      />
    </modal>

    <MobileMenu
      :class="{
        'mobile-menu_opened': menuOpened && windowWidth < 1366
      }"
      @close="menuOpened = false"
      @logout="logout"
      @formOpen="openForm"
      v-click-outside="mobileMenuClickOutside"
    />

    <div 
      class="feed-mobile__list"
      v-if="mobileFeedListShowFlag"
      v-click-outside="mobileFeedListHide"
    >
      <div 
        v-for="(feed, key) in feeds"
        :key="key"
        @click="feedChange(key)"
      >
        <span class="feed-mobile__feed pointer" :class="{
          'feed-mobile__feed_active': feed == activeFeed
        }">{{ feed.title }}</span>
      </div>
    </div>

    <div
      v-if="menuOpened && windowWidth >= 1366"
      class="header__desctop-menu"
      v-click-outside="hideDesctopMenu"
    >
      <button
        v-for="(desctopItem, key) in desctopMenu"
        :key="key"
        class="header__desctop-menu-item pointer no-select"
        @click="desctopMenuItemSelect(desctopItem)"
      >
        {{ desctopItem.caption }}
      </button>

      <button 
        class="header__desctop-logout pointer no-select"
        @click="logout"
      >
        Выйти
      </button>

    </div>

    <!-- HEADER -->

    <header class="header">

      <div class="header__menu-block">
        <div 
          class="menu-short pointer"
          @click="menuOpened = true"
        >
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>

      <div 
        v-if="$route.name == 'index'"
        class="feed-mobile"
        :class="{
          'header__feed-type_search-opened': searchOpened && windowWidth > 500
        }"
      >
        <div 
          class="feed-mobile__active"
          :class="{
            'feed-mobile__active_hide': mobileFeedListShow
          }"
          @click="mobileFeedListShow = true"
        >
          {{ activeFeed.title }}
          <img src="/assets/images/i-arrow_white.svg" alt="">
        </div>

      </div>

      <div class="header__logo">
        <router-link to="/">FlamingoArt</router-link>
      </div>

      <div 
        class="header__feed-type"
      >
        <div 
          v-for="(feed, key) in feeds"
          :key="key"
          class="header__feed"
          :class="{
            'header__feed_active': feed.active
          }"
          @click="feedChange(key)"
        >
          {{ feed.title }}
        </div>
      </div>
      
      <div 
        class="header__search-block"
        :class="{
          'header__search-block_wide': searchOpened && windowWidth > 500 && windowWidth < 1366
        }"
      >
        <div 
          v-if="windowWidth < 1366"
          class="search-short"
        >

            <img 
              v-if="(!searchOpened && windowWidth > 500) || windowWidth < 500"
              key="lupa"
              class="pointer"
              src="/assets/images/i-search.svg"
              @click="searchOpened = !searchOpened"
            >

            <search 
              v-if="shortSearchOpened"
              key="pole"
              class="search-short__field"
              :class="{
                'search-short__field_wide': searchOpened && windowWidth > 500
              }"
              v-click-outside="() => searchOpened = false"
              @mobile-close="searchOpened = false"
            />

        </div>
        <div class="search-full">
          <search/>
        </div>
      </div>

      <div
        v-if="!$isEmpty(user)"
        class="header__icons-bar"
      >

        <div class="header__messages">
          <img src="/assets/images/i-message.svg" alt="">
        </div>

        <div class="header__notifications">
          <img src="/assets/images/i-notification.svg" alt="">
        </div>

        <router-link to="/profile" class="header__user-login">
          <span class="header__user-login">{{ user.login }}</span>
        </router-link>

        <div class="header__user">

          <div class="headet__user-avatar">
            <router-link to="/profile">
              <img 
                :src="user.avatar || '/assets/images/unknown-user.png'"
                alt=""
              >
            </router-link>
          </div>

          <img
            class="header__user-menu"
            src="/assets/images/i-arrow_small.svg"
            alt=""
            @click="openDesctopMenu"
          >

        </div>
        
      </div>

      <div 
        v-else
        class="sign-buttons"
      >
        <button class="btn btn_no-bg" @click="openForm('auth')">
          Войти
        </button> 

        <button class="btn" @click="openForm('reg')">
          Регистрация
        </button>

      </div>

    </header>
  </div>
</template>


<script>

import MobileMenu from './MobileMenu.vue'

import Search from '../Search.vue'
import SignForm from '../SignForm.vue'

export default {
  components: {

    MobileMenu,

    Search,
    SignForm
  },
  data() {
    return {

      mobileMenuOpened: false,

      menuOpened: false,

      desctopMenuShow: false,

      mobileFeedListShow: false,

      desctopMenu: [
        {
          caption: 'Мой профиль',
          link: '/profile',
        },
        {
          caption: 'Избранное',
          link: '',
        },
        {
          caption: 'Мои сделки',
          link: '',
        },
        {
          caption: 'Настройки',
          link: '',
        },
        {
          caption: 'Помощь',
          link: '',
        },
      ],

      formMode: 'reg',
      searchOpened: false,
      feeds: {
        main: {
          title: "Главная",
          active: true
        },
        popular: {
          title: "Популярное",
          active: false
        },
        new: {
          title: "Новое",
          active: false
        }
      }
    }
  },
  computed: {
    mobileFeedListShowFlag() {
      return this.mobileFeedListShow && this.windowWidth < 1366
    },
    activeFeed() {
      for (const index in this.feeds) {
        if (this.feeds[index].active) {
          return this.feeds[index]
        }
      }
    },
    windowWidth() {
      return this.$store.getters.windowWidth
    },
    formOpen() {
      return this.$store.getters.isSignFormOpened
    },
    user() {
      return this.$store.getters.user
    },
    shortSearchOpened: {
      get() {
        return this.searchOpened // && this.$store.getters.windowWidth < 1024
      },
      set(value) {
        this.searchOpened = value
      }
    },
  },
  watch: {
    searchOpened() {
      if (this.windowWidth < 1366 && this.searchOpened) {
        setTimeout(() => {
          document.querySelector("#main-searh").focus()
        }, 10)
      }
    }
  },
  created() {
    if (this.$cookies.get('items-list-feed')) {
      this.feedChange(this.$cookies.get('items-list-feed'))
    }
  },
  methods: {
    feedChange(key) {

      for (const index in this.feeds) {
        if (this.feeds[index].active && key == index) {
          this.mobileFeedListShow = false
          return
        }
        this.feeds[index].active = false
      }
      this.feeds[key].active = true
      this.$eventBus.$emit('feed-change', key)
      this.$cookies.set('items-list-feed', key, "1d")

      this.mobileFeedListShow = false

    },
    openForm(mode) {
      this.formMode = mode
      this.$modal.show('signForm')
    },
    setFormMode(mode) {
      this.formMode = mode
    },
    openDesctopMenu() {
      this.menuOpened = true
    },
    hideDesctopMenu() {
      if (event.target.classList.contains('header__user-menu')) {
        return
      }
      this.menuOpened = false
    },
    mobileFeedListHide() {
      this.mobileFeedListShow = false
    },
    desctopMenuItemSelect(item) {
      
      this.$router.push(item.link)

      this.hideDesctopMenu()

    },
    logout() {

      this.$http.get('/api/auth/logout')
        .then(response => {
          const data = response.data

          this.$store.commit('setUser', {})

          this.$router.push('/')

          this.$root.showNotification(data.success, 'success')

        })

    },
    mobileMenuClickOutside() {
      const isFullyShowed = document.getElementById('mobile-menu').getBoundingClientRect().left == 0
      if (isFullyShowed) {
        this.menuOpened = false
      }
    }
  },
}
</script>