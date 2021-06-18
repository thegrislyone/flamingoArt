<template>
  <div class="mobile-menu-wrapper">

    <div 
      v-if="opened"
      class="mobile-menu__overlay"
      @click="$emit('close')"
    ></div>

    <aside
      id="mobile-menu" 
      class="mobile-menu"
      :class="{
        'mobile-menu_opened': opened
      }"
    >

      <button 
        class="mobile-menu__close pointer"
        @click="$emit('close')"
      ></button>

      <div 
        v-if="isAuthorized"
        class="mobile-menu__authorized"
      >
        <div class="mobile-menu__user">

          <div class="mobile-menu__avatar">
            <img 
              class="pointer"
              :src="user.avatar || '/assets/images/unknown-user.png'"
              @click="goTo('/profile')"
            >
          </div>

          <div 
            class="mobile-menu__nickname pointer"
            @click="goTo('/profile')"
          >
            {{ user.login }}
          </div>

        </div>

        <div class="mobile-menu__menu-items mobile-menu__menu-items_auth">
          <div
            class="mobile-menu__menu-item"
          >
            <button class="pointer" @click="goTo('/')">Главная</button>
          </div>
          <div
            class="mobile-menu__menu-item"
          >
            <button class="pointer" @click="goTo('/profile')">Мой профиль</button>
          </div>
          <div
            class="mobile-menu__menu-item mobile-menu__messages"
          >
            <button class="pointer" @click="goTo('/chat')">Сообщения</button>

            <div v-if="$store.getters.unreadedMessages" class="chat-list-item__new-messages">{{ $store.getters.unreadedMessages | amountPrettify }}</div>
            
          </div>
          <div
            class="mobile-menu__menu-item mobile-menu__notifications"
          >
            <button class="pointer" @click="goTo('/notifications')">Уведомления</button>

            <div v-if="$store.getters.uncheckedNotifications" class="chat-list-item__new-messages">{{ $store.getters.uncheckedNotifications | amountPrettify }}</div>

          </div>
        </div>

        <div class="mobile-menu__auth-menu">
          <div class="mobile-menu__auth-menu-item">
            <button class="pointer" @click="goTo('/profile?mode=favorites')">Избранное</button>
          </div>
          <div class="mobile-menu__auth-menu-item">
            <button class="pointer" @click="goTo('/my-deals')">Мои сделки</button>
          </div>
          <div class="mobile-menu__auth-menu-item">
            <button class="pointer" @click="goTo('/profile-settings')">Настройки</button>
          </div>
          <div class="mobile-menu__auth-menu-item">
            <button class="pointer" @click="goTo('/help')">Помощь</button>
          </div>
          <div class="mobile-menu__auth-menu-item mobile-menu__auth-menu-logout">
            <button class="pointer" @click="logout">Выйти</button>
          </div>
        </div>

      </div>

      <div
        v-else
        class="mobile-menu__not-authorized"
      >

        <div class="mobile-menu__logo">
          <router-link to="/">FlamingoArt</router-link>
        </div>

        <div class="mobile-menu__menu-items mobile-menu__menu-items_not-auth">
          <div
            class="mobile-menu__menu-item"
          >
            <button class="pointer" @click="goTo('/')">Главная</button>
          </div>
          <div
            class="mobile-menu__menu-item"
          >
            <button class="pointer" @click="goTo('/help')">Помощь</button>
          </div>
        </div>

        <div class="mobile-menu__bottom-part">
          <div class="mobile-menu__text">
            Войдите или создайте аккаунт на сайте, чтобы получить доступ ко всем функциям:
          </div>

          <div class="mobile-menu__buttons">
            <button 
              class="btn mobile-menu__btn mobile-menu__login"
              @click="formOpen('auth')"
            >
              Войти
            </button>
            <button 
              class="btn mobile-menu__btn mobile-menu__register"
              @click="formOpen('reg')"
            >
              Регистрация
            </button>
          </div>
        </div>

      </div>

    </aside>

  </div>
  
</template>

<script>
export default {
  props: {
    opened: Boolean
  },
  data() {
    return {
      notAuthorizedMenu: [
        {
          caption: 'Главная',
          link: ''
        },
        {
          caption: 'Помощь',
          link: ''
        }
      ]
    }
  },
  filters: {
    amountPrettify(value) {

      if (!value) return ''

      return (Number(value) > 9) ? '9+' : value

    }
  },
  computed: {
    isAuthorized() {
      return this.$store.getters.isAuthorizate
    },
    user() {
      return this.$store.getters.user
    },
  },
  created() {
    
  },
  methods: {
    goTo(link) {

      if (!link) {
        return
      }

      this.$router.push(link)

      this.$emit('close')

    },
    logout() {
      this.$emit('logout')
      this.$emit('close')
    },
    formOpen(mode) {

      this.$emit('close')

      setTimeout(() => {
        this.$emit('formOpen', mode)
      })

    }
  }
}
</script>