<template>
  <header class="header">

    <auth-form
      v-if="formOpen"
      @form-close="$store.commit('setFormOpenedStatus', false)"
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
          <img
            class="header__full-logo"
            src="assets/images/logo-word.png"
          >
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
        <div class="header__group-itm header__short-search-itm">
          <img 
            class="header__search-icon"
            src="assets/images/search.png"
            @click="searchOpened = true"
          >
          <div class="header__nickname">{{ 'Thegrislyone' }}</div>
        </div>
        <div class="header__group-itm header__user-itm">
          <img 
            @click="$store.commit('setFormOpenedStatus', true)"
            class="header__user-img"
            src="assets/images/unknown-user.png"
          >
        </div>
      </div>
    </div>

  </header>
</template>


<script>
import Search from '../Search.vue'
import AuthForm from '../AuthForm.vue'

export default {
  components: {
    Search,
    AuthForm
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
      return this.$store.getters.isFormOpened
    }
  },
  methods: {
  }
}
</script>