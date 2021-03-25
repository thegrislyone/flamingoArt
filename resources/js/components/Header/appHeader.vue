<template>
  <header class="header">

    <modal
      name="signForm"
      :classes="['modal','sign-in-form']"
      :adaptive="true"
      :height="'auto'"
      :scrollable="true"
      :max-width="356"
      :pivot-y="0"
    >
      <sign-form/>
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

    <div class="header__icons-bar">
      <div class="header__messages">
        <img src="/assets/images/i-message.svg" alt="">
      </div>
      <div class="header__notifications">
        <img src="/assets/images/i-notification.svg" alt="">
      </div>
      <div class="header__user">
        <div class="headet__user-avatar">
          <img 
            v-if="!$isEmpty($store.getters.user)"
            src="/assets/images/avatar.jpg"
            alt=""
          >
          <img 
            v-else
            src="/assets/images/unknown-user.png"
            alt=""
            @click="$modal.show('signForm')"
          >
        </div>
        <img
          class="header__user-menu"
          src="/assets/images/i-arrow_small.svg"
          alt=""
          >
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
    },
    shortSearchOpened: {
      get() {
        return this.searchOpened && this.$store.getters.windowWidth < 560
      },
      set(value) {
        this.searchOpened = value
      }
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