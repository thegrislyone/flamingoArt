<template>
  <div class="help">

    <h2 v-if="!mode" class="help__headline">Помощь</h2>

    <div class="help__container">

      <div 
        v-if="!mode || windowWidth >= 1024"
        class="help__menu"
      >

        <div class="help__menu-wrp">

          <div v-if="windowWidth >= 1024">Помощь</div>

          <div v-if="windowWidth < 1024" :class="{ 'help__menu-item_active': mode == 'manual' }"><router-link to="/help?mode=manual">Руководство пользователя</router-link></div>
          <div :class="{ 'help__menu-item_active': mode == 'privacy' }"><router-link to="/help?mode=privacy">Политика конфиденциальности</router-link></div>
          <div :class="{ 'help__menu-item_active': mode == 'terms' }"><router-link to="/help?mode=terms">Условия использования</router-link></div>
          <div :class="{ 'help__menu-item_active': mode == 'contacts' }"><router-link to="/help?mode=contacts">Контакты</router-link></div>
          <div :class="{ 'help__menu-item_active': mode == 'ask' }"><router-link to="/help?mode=ask">Задать вопрос</router-link></div>
        </div>

      </div>

      <div 
        v-if="mode || windowWidth >= 1024"
        class="help__content"
      >

        <div v-if="mode == 'manual'">
          <h1 class="help__headline">Данный раздел находится в разработке</h1>
        </div>

        <div v-if="mode == 'privacy'">

          <h1 class="help__headline">{{ activePoint.headline }}</h1>

          <div class="help__text" v-html="activePoint.text"></div>

        </div>

        <div v-if="mode == 'terms'">
          <h1 class="help__headline">Данный раздел находится в разработке</h1>
        </div>       
        
        <div v-if="mode == 'contacts'">

          <h1 class="help__headline">Контакты</h1>

          <div class="help__contacts-block">
            <div class="help__contacts-headline">Электронная почта</div>
            <div class="help__contacts-text">diplomaflamingoart@gmail.com</div>
          </div>

        </div>

        <div v-if="mode == 'ask'">

          <h1 class="help__headline">Задать вопрос</h1>

          <template v-if="!sended">

            <div class="help__ask-text">Напишите суть вашего вопроса в форме ниже. Ответ придёт вам на почту.</div>

            <form @submit.prevent="formSubmit">

              <div class="form-block">
                <div class="help__ask-label">Ваша электронная почта</div>
                <input class="help__ask-field" type="text" placeholder="Email" v-model="email">
              </div>

              <div class="form-block">
                <div class="help__ask-label">Описание</div>
                <textarea class="help__ask-field help__ask-textarea" placeholder="Опишите проблему" v-model="description"></textarea>
              </div>

              <button class="help__ask-button btn">Отправить</button>

            </form>  

          </template>

          <template v-else>

            <div class="help__ask-text">Ваше обращение получено. Мы ответим вам в ближайшее время.</div>

            <button class="btn" @click="writeAgain">Отправить ещё</button>

          </template>

        </div>

      </div>

    </div>
    
    
    
  </div>
</template>

<script>

import documents from '../documents/documents.json'
import { required, email, sameAs, minLength, maxLength } from 'vuelidate/lib/validators'

export default {
  data() {
    return {
      email: '',
      description: '',
      sended: false
    }
  },
  validations: {

    email: {
      required: required,
      email: email,
    },

    description: {
      required: required,
      minLength: minLength(24),
      maxLength: maxLength(255)
    }

  },
  computed: {
    mode() {
      return this.$route.query.mode || null
    },
    activePoint() {
      return (this.mode) ? documents[this.mode] : null
    },
    windowWidth() {
      return this.$store.getters.windowWidth
    }
  },
  watch: {
    windowWidth() {
      if (this.windowWidth >= 1024 && (!this.mode || this.mode == 'manual')) {
        this.$router.push('/help?mode=privacy')
      }
    }
  },
  created() {
    
    if (this.windowWidth >= 1024 && !this.mode) {
      this.$router.push('/help?mode=privacy')
    }

  },
  methods: {
    formSubmit() {

      console.log(this.$v)
      
      if (this.$v.$invalid) {

        if (!this.$v.email.email || !this.$v.email.required) {
          this.$root.showNotification({
            'type': 'error',
            'title': 'Формат email неверный'
          })
        } else if (!this.$v.description.minLength || !this.$v.description.required) {
          this.$root.showNotification({
            'type': 'error',
            'title': 'Минимальная длина описания - 24 символа'
          })
        } else if (!this.$v.description.maxLength) {
          this.$root.showNotification({
            'type': 'error',
            'title': 'Максимальная длина описания - 255 символов'
          })
        }

        return

      }

      this.$http.post('/api/feedbacks/send-feedback', {
        email: this.email,
        description: this.description
      })
        .then(response => {
          const data = response.data

          this.sended = true

          console.log(data)
        })

    },
    writeAgain() {
      this.description = ''
      this.email = ''
      this.sended = false
    }
  }
}
</script>
