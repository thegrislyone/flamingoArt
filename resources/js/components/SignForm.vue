<template>
  <div class="sign-form">

    <div class="sign-form__header">
      <div class="sign-form__logo">
        <img src="../../../public/assets/images/logo-word.png">
      </div>
      <div 
        class="close close_white sign-form__close"
        @click="$emit('form-close')"
      ></div>
    </div>

    <div class="sign-form__form-wrp">

      <div 
        class="sign-form__block sign-form__auth"
      >
        <h2 class="sign-form__headline h h_pink">{{ activeForm.headline | capitalize }}</h2>

        <div class="sign-form__form">

          <div class="sign-form__fields">
            <text-input
              v-for="(field, index) in activeForm.fields"
              :key="index"
              :placeholder="field.placeholder"
              :required="field.required"
            />
          </div>

          <div class="sign-form__button">
            <button class="btn">{{ activeForm.buttonText | capitalize }}</button>
          </div>

          <div class="sign-form__link-block">
            <span>{{ activeForm.lowerText.text | capitalize }}</span>
            <span class="link" @click="$store.commit('setSignFormMode', activeForm.changeKey)">{{ activeForm.lowerText.link | capitalize }}.</span>
          </div>
          
        </div>
      </div>


    </div>

  </div>
</template>

<script>
import TextInput from './TextInput.vue'
import { required, minLength, maxLength, sameAs } from 'vuelidate/lib/validators'

export default {
  components: {
    TextInput
  },
  data() {
    return {
      form: {
        authForm: {
          headline: "вход",
          changeKey: "registration",
          buttonText: "войти",
          lowerText: {
            text: "нет аккаунта?",
            link: "регистрация"
          },
          fields: [
            {
              placeholder: "Email",
              required: true
            },
            {
              placeholder: "Пароль",
              required: true
            }
          ]
        },
        registrationForm: {
          headline: "регистрация",
          changeKey: "authorization",
          buttonText: "зарегестрироваться",
          lowerText: {
            text: "есть аккаунт?",
            link: "войти"
          },
          fields: [
            {
              placeholder: "Email",
              required: true
            },
            {
              placeholder: "Имя и фамилия",
              required: true
            },
            {
              placeholder: "Имя пользователя",
              required: true
            },
            {
              placeholder: "Пароль",
              required: true
            },
            {
              placeholder: "Повторите пароль",
              required: true
            }
          ]
        }
      }
    }
  },
  computed: {
    formMode() {
      return this.$store.getters.signFormMode
    },
    activeForm() {
      if (this.formMode === 'registration') {
        return this.form.registrationForm
      } else if (this.formMode === 'authorization') {
        return this.form.authForm
      }
    }
  },
  validations: {
  },
  created() {
  },
  mounted() {
  }
}
</script>