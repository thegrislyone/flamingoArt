<template>
  <div class="header">

    <auth-form
      v-if="formOpen"
      @form-close="formOpen = false"
    />

    <div class="header__wrp">
      <div class="header__left-side header__search-side">
        <div class="header__item">
          <img 
            class="header__menu-icon"
            src="../../../../public/assets/images/menu-icon.png"
          >
          <img 
            class="header__full-logo"
            src="../../../../public/assets/images/logo-word.png"
          >
        </div>
        <div class="header__item header__search-block">
          <img
            class="header__short-logo"
            src="../../../../public/assets/images/logo-icon.png"
            alt=""
          >
          <search
            class="header__search"
            placeholder="Поиск..."
          />
        </div>
      </div>
      <div class="header__right-side">
        <div class="header__item">
          <img 
            class="header__short-search"
            src="../../../../public/assets/images/search.png"
          >
          <span class="header__user">Thegrislyone</span>
        </div>
        <div class="header__item">
          <img 
            src="../../../../public/assets/images/unknown-user.png"
            class="header__user-icon"
            @click="formOpen = true"
          >
        </div>
      </div>
    </div>
  </div>
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
      formOpen: true,
    }
  },
  computed: {
    windowWidth() {
      return this.$store.getters.windowWidth
    }
  }
}
</script>