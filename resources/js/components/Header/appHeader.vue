<template>
  <header class="header">

    <sign-form
      v-if="formOpen"
      @form-close="$store.commit('setSignFormOpenedStatus', false)"
    />

    <div 
      v-if="searchOpened"
      class="header__short-search"
    >
      <search
        :close="true"
        @search-close="searchOpened = false"
      />
    </div>

    <div 
      v-if="!searchOpened"
      class="header__wrp"
      :class="{
        'header__wrp_search-opened': searchOpened
      }"
    >
      <div class="header__group header__left-group">
        <div class="header__group-itm header__menu-itm">
          <div class="header__menu-icon">
            <div v-for="index in 3" :key="index"></div>
          </div>
          <router-link
            class="header__full-logo"
            to="/"
          >
            <img
              src="assets/images/logo-word.png"
            >
          </router-link>
        </div>
        <div class="header__group-itm header__short-logo-itm">
          <img 
            class="header__short-logo"
            src="assets/images/logo-icon.png"
          >
          <search
            class="header__search"
          />
        </div>
      </div>
      <div class="header__group header__right-group">
        <div 
          class="header__group-itm header__short-search-itm"
        >
          <img 
            class="header__search-icon"
            src="assets/images/search.png"
            @click="searchOpened = true"
          >
        </div>
        <div 
          v-if="!$store.getters.isAuthorizate"
          class="header__group-itm header__user-itm"
          @click="$store.commit('setSignFormOpenedStatus', true)"
        >
          <div class="header__nickname">Войти</div>
          <img 
            class="header__user-img"
            src="assets/images/unknown-user.png"
          >
        </div>
        <router-link 
          v-else
          to="/profile"
          class="header__group-itm header__user-itm"
        >
          <div class="header__nickname">{{ 'thegrislyone' }}</div>
          <img 
            class="header__user-img"
            src="assets/images/item2.jpg"
          >
        </router-link>
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
    }
  },
  computed: {
    windowWidth() {
      return this.$store.getters.windowWidth
    },
    formOpen() {
      return this.$store.getters.isSignFormOpened
    }
  },
  methods: {
  }
}
</script>