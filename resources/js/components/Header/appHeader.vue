<template>
  <header class="header">

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
        <img src="/assets/images/i-search.svg">
      </div>
      <div class="search-full">
        <search/>
      </div>
    </div>

    <div class="header__icons-bar">
      <div class="header__messages">
        <img src="/assets/images/i-message.svg" alt="">
      </div>
      <div class="header__notifications">
        <img src="/assets/images/i-notification.svg" alt="">
      </div>
      <div class="header__user">
        <div class="headet__user-avatar">
          <img src="/assets/images/avatar.jpg" alt="">
        </div>
        <img
          class="header__user-menu"
          src="/assets/images/i-arrow_small.svg"
          alt=""
          >
        <!-- <img src="/assets/images/i-.svg" alt=""> -->
      </div>
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
    }
  },
  methods: {
    feedChange(key) {
      for (const index in this.feeds) {
        this.feeds[index].active = false
      }
      this.feeds[key].active = true
    }
  }
}
</script>