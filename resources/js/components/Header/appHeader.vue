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
      <div class="menu-short">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>

    <div class="header__logo">
      <router-link to="/">FlamingoArt</router-link>
    </div>

    <div class="header__feed-type">
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
    
    <div class="header__search-block">
      <div class="search-short">
        <img 
          src="/assets/images/i-search.svg"
          @click="searchOpened = !searchOpened"
        >
        <transition name="fade">
          <search 
            v-if="shortSearchOpened"
            class="search-short__field"
            v-click-outside="() => searchOpened = false"
          />
        </transition>
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
      <span class="header__user-login">{{ user.login }}</span>
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
        return this.searchOpened && this.$store.getters.windowWidth < 960
      },
      set(value) {
        this.searchOpened = value
      }
    },
  },
  methods: {
    feedChange(key) {
      for (const index in this.feeds) {
        this.feeds[index].active = false
      }
      this.feeds[key].active = true
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