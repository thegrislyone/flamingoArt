<template>
  <div class="header-wrapper">

    <!-- SIGN FORM -->

    <modal
      name="signForm"
      :classes="['modal','sign-in-form']"
      :height="(windowWidth <= 560) ? '100%' : 'auto'"
      :scrollable="true"
      :min-width="320"
      :adaptive="true"
      :max-width="480"
      :shiftY="0.1"
    >
      <sign-form
        :mode="formMode"
        @setMode="setFormMode"
      />
    </modal>

    <!-- MOBILE MENU -->

    <MobileMenu
      :opened="menuOpened && windowWidth < 1366"
      @close="menuClose"
      @logout="logout"
      @formOpen="openForm"
    />

    <!-- MOBILE FEED LIST -->

    <div 
      class="feed-mobile__list"
      v-if="mobileFeedListShowFlag"
      v-click-outside="mobileFeedListHide"
    >

      <template v-if="recomendations">

        <div 
          v-for="(feed, key) in feeds"
          :key="key"
          @click="feedChange(key)"
        >
          <span class="feed-mobile__feed pointer" :class="{
            'feed-mobile__feed_active': key == activeFeed
          }">{{ feed.title }}</span>
        </div>

      </template>

      <template v-else>

        <div 
          v-for="(feed, key) in noRecomendationsFeeds"
          :key="key"
          @click="feedChange(key)"
        >
          <span class="feed-mobile__feed pointer" :class="{
            'feed-mobile__feed_active': key == activeFeed
          }">{{ feed.title }}</span>
        </div>

      </template>

      
    </div>

    <!-- DESCTOP MENU -->

    <div
      v-if="menuOpened && windowWidth >= 1366"
      class="header__desctop-menu"
      v-click-outside="menuClose"
    >

      <div class="header__desctop-menu-items">
        <button
          v-for="(desctopItem, key) in desctopMenu"
          :key="key"
          class="header__desctop-menu-item pointer no-select"
          @click="desctopMenuItemSelect(desctopItem)"
        >
          <span>{{ desctopItem.caption }}</span>
        </button>
      </div>

      <button 
        class="header__desctop-logout pointer no-select"
        @click="logout"
      >
        Выйти
      </button>

    </div>

    <!-- NOTIFICATIONS -->

    <div 
      v-if="notificationsShow"
      class="header__notifications-menu"
      v-click-outside="notificationsClose"
    >

      <h2 class="header__notifications-headline">Уведомления</h2>

      <div class="header__notifications-container">
        <notifications-item
          v-for="(notification, index) in notificationsList"
          :key="index"
          :data="notification"
          class="notifications-item_desctop-list"
          @message-checked="renewNotifications"
        />
      </div>

      <div 
        class="notifications-item__show-all pointer"
        @click="notificationsOpen"
      >
        Показать все
      </div>

    </div>

    <!-- HEADER -->

    <header class="header">

      <div class="header__menu-block">
        <div 
          class="menu-short pointer"
          @click="menuOpen"
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
          <template v-if="activeFeed">{{ feeds[activeFeed].title }}</template>
          <img src="/assets/images/i-arrow_white.svg" alt="">
        </div>

      </div>

      <div class="header__logo">
        <h1>
          <router-link 
            to="/"
            class="main-link"
          >
            <img src="/assets/images/i-logo.svg">
            FlamingoArt
          </router-link>
        </h1>
      </div>

      <div 
        class="header__feed-type"
      >

        <template v-if="recomendations">

          <div 
            v-for="(feed, key) in feeds"
            :key="key"
            class="header__feed"
            :class="{
              'header__feed_active': activeFeed == key
            }"
            @click="feedChange(key)"
          >
            {{ feed.title }}
          </div>

        </template>
        
        <template v-else>

          <div 
            v-for="(feed, key) in noRecomendationsFeeds"
            :key="key"
            class="header__feed"
            :class="{
              'header__feed_active': activeFeed == key
            }"
            @click="feedChange(key)"
          >
            {{ feed.title }}
          </div>

          <div 
            class="header__feed"
          >
            <router-link to="/help">Помощь</router-link>
          </div>

        </template>

        
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
          :class="{
            'search-short_320': windowWidth <= 500
          }"
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

        <button class="header__messages">

          <router-link to="/chat">

          <div v-if="$store.getters.unreadedMessages" 
            class="chat-list-item__new-messages chat-list-item__new-messages_small header__new-messages"
          >
            {{ $store.getters.unreadedMessages | amountPrettify }}
          </div>

          <img src="/assets/images/i-message.svg">

          </router-link>

        </button>

        <button 
          class="header__notifications"
          @click="notificationsOpen"
        >

          <div v-if="$store.getters.uncheckedNotifications" 
            class="chat-list-item__new-messages chat-list-item__new-messages_small header__new-messages"
          >
            {{ $store.getters.uncheckedNotifications | amountPrettify }}
          </div>
          
          <img src="/assets/images/i-notification.svg">

        </button>

        <div class="user-area" @click="menuOpen">

          <span class="header__user-login">{{ user.login }}</span>

          <div class="header__user">

            <div class="headet__user-avatar">
              <img 
                :src="user.avatar || '/assets/images/unknown-user.png'"
                alt=""
              >
            </div>

            <img
              class="header__user-menu"
              src="/assets/images/i-arrow_small.svg"
              alt=""
            >

          </div>

        </div>
        
      </div>

      <div 
        v-else
        class="sign-buttons"
      >
        <button class="sign-buttons__btn btn btn_no-bg" @click="openForm('auth')">
          Войти
        </button> 

        <button class="sign-buttons__btn btn" @click="openForm('reg')">
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

import NotificationsItem from '../NotificationsItem.vue'

import { debounce } from 'vue-debounce'

export default {
  components: {

    MobileMenu,

    Search,
    SignForm,

    NotificationsItem
  },
  data() {
    return {

      activeFeed: '',

      recomendations: null,

      renewNotifications: null,

      notificationsShow: false,

      menuOpened: false,

      mobileFeedListShow: false,

      desctopMenu: [
        {
          caption: 'Мой профиль',
          link: '/profile',
        },
        {
          caption: 'Избранное',
          link: '/profile?mode=favorites',
        },
        {
          caption: 'Мои сделки',
          link: '/my-deals',
        },
        {
          caption: 'Настройки',
          link: '/profile-settings',
        },
        {
          caption: 'Помощь',
          link: '/help',
        },
      ],

      formMode: 'reg',
      searchOpened: false,
      feeds: {
        main: {
          title: "Главная"
        },
        popular: {
          title: "Популярное"
        },
        new: {
          title: "Новое"
        }
      },
      noRecomendationsFeeds: {
        popular: {
          title: "Популярное"
        },
        new: {
          title: "Новое"
        }
      }
    }
  },
  filters: {
    amountPrettify(value) {

      if (!value) return ''

      return (Number(value) > 9) ? '9+' : value

    }
  },
  computed: {
    isAuthorizate() {
      return this.$store.getters.isAuthorizate
    },
    notificationsList() {
      return this.$store.state.notifications
    },
    mobileFeedListShowFlag() {
      return this.mobileFeedListShow && this.windowWidth < 1366
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
    windowWidth() {

      let bodyStyles = document.querySelector('body').style

      if (this.menuOpened && this.windowWidth >= 1366 && bodyStyles.overflowY == 'hidden') {
        bodyStyles.overflowY = 'scroll'
      }

      if (this.menuOpened && this.windowWidth < 1366 && bodyStyles.overflowY != 'hidden') {
        bodyStyles.overflowY = 'hidden'
      }
      
    },
    searchOpened() {
      if (this.windowWidth < 1366 && this.searchOpened) {
        setTimeout(() => {
          document.querySelector("#main-searh").focus()
        }, 10)
      }
    }
  },
  created() {

    // if (this.$cookies.get('items-list-feed')) {
    //   this.activeFeed = this.$cookies.get('items-list-feed')
    //   this.feedChange(this.$cookies.get('items-list-feed'))
    // } else {
    //   this.activeFeed = 'popular'
    // }

    this.getRecomendations()

    this.$eventBus.$on('login', this.loginGetRecomendations)

    this.renewNotifications = debounce(this.notificationsUpdate, 300)

  },
  methods: {

    loginGetRecomendations() {
      this.getRecomendations(true)
    },

    getRecomendations(login = false) {
      this.$http.get('/api/user/recomendations/get-recomendation')
        .then(response => {

          const data = response.data

          this.recomendations = data.recomendations

          if (this.isAuthorizate && this.recomendations) {
            if (this.$cookies.get('items-list-feed') && !login) {
              this.feedChange(this.$cookies.get('items-list-feed'))
            } else {
              this.feedChange('main')
            }
          } else {
            if (this.$cookies.get('items-list-feed') && this.$cookies.get('items-list-feed') != 'main') {
              this.feedChange(this.$cookies.get('items-list-feed'))
            } else {
              this.feedChange('popular')
            }
            
          }

        })
    },

    notificationsUpdate() {
      this.$store.dispatch('getNotifications')
    },

    notificationsOpen() {

      if (this.windowWidth >= 1366 && !this.notificationsShow) {

        this.notificationsShow = true
        
      } else {

        this.notificationsShow = false

        this.$router.push('/notifications')

      }

    },

    notificationsClose() {
      
      this.notificationsShow = false

    },

    menuOpen() {
      if (this.windowWidth < 1366) {
        document.querySelector('body').style.overflowY = 'hidden'
      }
      this.menuOpened = true
    },
    menuClose() {
      if (this.windowWidth < 1366) {
        document.querySelector('body').style.overflowY = 'auto'
      }
      this.menuOpened = false
    },

    feedChange(key) {

      // let activeFeeds = (this.isAuthorizate && this.recomendations) ? this.feeds : this.noRecomendationsFeeds

      // for (const index in activeFeeds) {
      //   if (activeFeeds[index].active && key == index) {
      //     this.mobileFeedListShow = false
      //     return
      //   }
      //   activeFeeds[index].active = false
      // }
      // activeFeeds[key].active = true

      if (this.activeFeed == key) {
        return
      }

      this.activeFeed = key
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
    mobileFeedListHide() {
      this.mobileFeedListShow = false
    },
    desctopMenuItemSelect(item) {
      
      this.$router.push(item.link)

      this.menuClose()

    },
    logout() {

      this.menuClose()

      this.$http.get('/api/auth/logout')
        .then(response => {
          const data = response.data

          this.$store.commit('setUser', {})

          this.$router.push('/')

          this.getRecomendations()

          this.$root.showNotification(data.notification)

        })

    },
  },
}
</script>