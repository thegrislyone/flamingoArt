<template>
  <header class="header">

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

    <div class="header__menu-block">
      <div class="menu-short pointer">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>

    <div 
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

      <div 
        class="feed-mobile__list"
        v-if="mobileFeedListShow"
        v-click-outside="() => mobileFeedListShow = false"
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

      <router-link to="/profile">
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
</template>


<script>
import Search from '../Search.vue'
import SignForm from '../SignForm.vue'

export default {
  components: {
    Search,
    SignForm
  },
  data() {
    return {

      mobileFeedListShow: false,

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
      if (this.windowWidth > 500 && this.windowWidth < 1366 && this.searchOpened) {
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
    }
  },
}
</script>