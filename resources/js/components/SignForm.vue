<template>
  <div class="sign-form">
    <img 
      class="sign-form__close"
      src="/assets/images/i-close.svg" 
      alt=""
      @click="$modal.hide('signForm')"
    >
    <transition name="fade" mode="out-in">

      <!-- registration form -->

      <div 
        v-if="mode == 'reg'"
        class="sign-form__reg"
        key="registration"
      >
        <h1 class="sign-form__form sign-form__headline">Регистрация</h1>
        
        <form class="sign-form__form" @submit.prevent="formSubmit">

          <form-group
            v-for="(field, key) in regForm.fields"
            :ref="field.name"
            :key="key"
            :formData="field"
            :showErrorsOnlyBlur="true"
            :v="$v.regFormModel[field.name]"
            @error="setValidationError"
            @clearErrors="setValidationError('')"
            @validateInput="dataCheck"
            @focus="anyFocus = true"
            @blur="anyFocus = false"
            v-model="$v.regFormModel[field.name].$model"
          />

          <div v-if="!anyFocus" class="sign-form__validation-error">
            <template>{{ validationError }}</template>
          </div>

          <form-group
            class="sign-form__button sign-form__button_reg"
            :class="{
              'sign-form__button_error': validationError && !anyFocus
            }"
            @buttonClick="formSubmit"
            :formData="regForm.buttons"
          />

          <div class="sign-form__confidentiality">

            <div class="sign-form__checkbox-wrp">

              <input 
                type="checkbox" 
                id="confidentiality"
                v-model="confirmConfidentiality"
              >
              <label for="confidentiality"></label>

            </div>
            <div class="sign-form__conf-text">
              Я принимаю <router-link to="#">Условия использования</router-link> и <router-link to="#">Политику конфиденциальности</router-link>
            </div>

          </div>

          <div class="sign-form__socials">

            <div class="sign-form__social sign-form__vk">

              <span 
                class="sign-form__social-container"
                @click="authThroughSocials('vk')"
              >

                <svg class="sign-form__social-icon" width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M19.2804 1.02984C19.414 0.580625 19.2804 0.25 18.6376 0.25H16.5107C15.969 0.25 15.7198 0.533906 15.5862 0.850156C15.5862 0.850156 14.5029 3.47719 12.9718 5.18062C12.477 5.67297 12.2495 5.83109 11.9787 5.83109C11.8451 5.83109 11.6393 5.67297 11.6393 5.22375V1.02984C11.6393 0.490781 11.4876 0.25 11.0398 0.25H7.69593C7.35648 0.25 7.15426 0.501563 7.15426 0.735156C7.15426 1.24547 7.91982 1.36406 7.99926 2.80156V5.92094C7.99926 6.60375 7.87648 6.72953 7.60565 6.72953C6.88343 6.72953 5.12843 4.09172 4.08843 1.07297C3.87898 0.487188 3.67315 0.25 3.12787 0.25H1.00093C0.394262 0.25 0.271484 0.533906 0.271484 0.850156C0.271484 1.41078 0.993707 4.19594 3.63343 7.87594C5.39204 10.388 7.86926 11.75 10.1226 11.75C11.4768 11.75 11.6429 11.4481 11.6429 10.927C11.6429 8.52641 11.5201 8.3 12.199 8.3C12.5132 8.3 13.0548 8.45813 14.3187 9.66922C15.7632 11.1067 16.0015 11.75 16.8104 11.75H18.9373C19.544 11.75 19.8509 11.4481 19.674 10.8516C19.2695 9.59734 16.5359 7.01703 16.4132 6.84453C16.099 6.44203 16.1893 6.26234 16.4132 5.90297C16.4168 5.89937 19.0132 2.2625 19.2804 1.02984Z"/>
                </svg>

                <span>Войти через Вконтакте</span>

              </span>

            </div>

            <div class="sign-form__social sign-form__google">

              <span 
                class="sign-form__social-container"
                @click="authThroughSocials('google')"
              >

                <svg class="sign-form__social-icon" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M16.271 8.18536C16.271 12.7077 13.2022 15.9261 8.67036 15.9261C4.32535 15.9261 0.816406 12.3849 0.816406 7.99999C0.816406 3.61505 4.32535 0.0738525 8.67036 0.0738525C10.7859 0.0738525 12.5657 0.856878 13.9369 2.14807L11.7993 4.22229C9.00288 1.49928 3.80281 3.54473 3.80281 7.99999C3.80281 10.7645 5.99115 13.005 8.67036 13.005C11.7803 13.005 12.9457 10.755 13.1294 9.58841H8.67036V6.8622H16.1474C16.2203 7.2681 16.271 7.65801 16.271 8.18536Z" />
                </svg>

                <span>Войти через Google</span>

              </span>

            </div>

          </div>

        </form>

      </div>

      <!-- authentification form -->

      <div 
        v-else-if="mode == 'auth'"
        class="sign-form__form sign-form__auth"
        key="authorization"
      >
        <h1 class="sign-form__headline">Вход</h1>

        <form class="sign-form__form" @submit.prevent="formSubmit">
          <form-group
            v-for="(field, key) in authForm.fields"
            :ref="field.name"
            :key="key"
            :formData="field"
            :showValidationsError="buttonClicked"
            :v="$v.authFormModel[field.name]"
            @error="setValidationError"
            @clearErrors="setValidationError('')"
            @validateInput="dataCheck"
            v-model="$v.authFormModel[field.name].$model"
          />

          <div v-if="buttonClicked" class="sign-form__validation-error">
            {{ validationError }}
          </div>

          <form-group
            class="sign-form__button sign-form__button_auth"
            :class="{
              'sign-form__button_error': validationError && buttonClicked
            }"
            @buttonClick="formSubmit"
            :formData="authForm.buttons"
          />

          <div class="sign-form__change-mode">
            Нет аккаунта? <span class="sign-form__link sign-form__link_pink" @click="changeMode('reg')">Регистрация</span>
          </div>

          <div class="sign-form__reset-password">
            <span class="sign-form__link">Забыли пароль?</span>
          </div>

          <div class="sign-form__socials">

            <div class="sign-form__social sign-form__vk">

              <span 
                class="sign-form__social-container"
                @click="authThroughSocials('vk')"
              >

                <svg class="sign-form__social-icon" width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M19.2804 1.02984C19.414 0.580625 19.2804 0.25 18.6376 0.25H16.5107C15.969 0.25 15.7198 0.533906 15.5862 0.850156C15.5862 0.850156 14.5029 3.47719 12.9718 5.18062C12.477 5.67297 12.2495 5.83109 11.9787 5.83109C11.8451 5.83109 11.6393 5.67297 11.6393 5.22375V1.02984C11.6393 0.490781 11.4876 0.25 11.0398 0.25H7.69593C7.35648 0.25 7.15426 0.501563 7.15426 0.735156C7.15426 1.24547 7.91982 1.36406 7.99926 2.80156V5.92094C7.99926 6.60375 7.87648 6.72953 7.60565 6.72953C6.88343 6.72953 5.12843 4.09172 4.08843 1.07297C3.87898 0.487188 3.67315 0.25 3.12787 0.25H1.00093C0.394262 0.25 0.271484 0.533906 0.271484 0.850156C0.271484 1.41078 0.993707 4.19594 3.63343 7.87594C5.39204 10.388 7.86926 11.75 10.1226 11.75C11.4768 11.75 11.6429 11.4481 11.6429 10.927C11.6429 8.52641 11.5201 8.3 12.199 8.3C12.5132 8.3 13.0548 8.45813 14.3187 9.66922C15.7632 11.1067 16.0015 11.75 16.8104 11.75H18.9373C19.544 11.75 19.8509 11.4481 19.674 10.8516C19.2695 9.59734 16.5359 7.01703 16.4132 6.84453C16.099 6.44203 16.1893 6.26234 16.4132 5.90297C16.4168 5.89937 19.0132 2.2625 19.2804 1.02984Z"/>
                </svg>

                <span>Войти через Вконтакте</span>

              </span>

            </div>

            <div class="sign-form__social sign-form__google">

              <span 
                class="sign-form__social-container"
                @click="authThroughSocials('google')"
              >

                <svg class="sign-form__social-icon" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M16.271 8.18536C16.271 12.7077 13.2022 15.9261 8.67036 15.9261C4.32535 15.9261 0.816406 12.3849 0.816406 7.99999C0.816406 3.61505 4.32535 0.0738525 8.67036 0.0738525C10.7859 0.0738525 12.5657 0.856878 13.9369 2.14807L11.7993 4.22229C9.00288 1.49928 3.80281 3.54473 3.80281 7.99999C3.80281 10.7645 5.99115 13.005 8.67036 13.005C11.7803 13.005 12.9457 10.755 13.1294 9.58841H8.67036V6.8622H16.1474C16.2203 7.2681 16.271 7.65801 16.271 8.18536Z" />
                </svg>

                <span>Войти через Google</span>

              </span>

            </div>

          </div>

        </form>

      </div>
    </transition>
  </div>
</template>

<script>

import FormGroup from './FormGroup.vue'
import { required, email, sameAs, minLength, maxLength } from 'vuelidate/lib/validators'

export default {
  components: {
    FormGroup
  },
  props: {
    mode: {
      type: String
    }
  },
  data() {
    return {

      confirmConfidentiality: false,

      validationError: '',
      buttonClicked: false,
      anyFocus: false,
      formError: false,

      authFormModel: {},
      regFormModel: {},

      authForm: {
        submit: '/api/auth/login',
        fields: [
          {
            name: 'login',
            requireServerValidation: false,
            class: 'sign-form__field',
            type: 'text',
            placeholder: 'Логин',
            minLength: 2,
            maxLength: 32,
          },
          {
            name: 'password',
            requireServerValidation: false,
            class: 'sign-form__field',
            type: 'password',
            placeholder: 'Пароль',
            minLength: 6,
            maxLength: 54,
          },
        ],
        buttons: {
          type: 'buttons',
          left: [],
          right: [],
          center: [
            {
              name: "authorizate",
              type: "button",
              title: "Войти",
              action: "",
              method: "POST",
              type: 'button'
            }
          ]
        },
      },
      regForm: {
        submit: '/api/auth/register',
        fields: [
          {
            name: 'login',
            requireServerValidation: true,
            class: 'sign-form__field',
            type: 'text',
            placeholder: 'Логин',
            minLength: 2,
            maxLength: 32,
          },
          {
            name: 'email',
            requireServerValidation: true,
            class: 'sign-form__field',
            type: 'email',
            placeholder: 'E-mail',
          },
          {
            name: 'password',
            requireServerValidation: false,
            class: 'sign-form__field',
            type: 'password',
            placeholder: 'Пароль',
            minLength: 6,
            maxLength: 54,
          }
        ],
        buttons: {
          type: 'buttons',
          left: [],
          right: [],
          center: [
            {
              name: "create-account",
              title: "Создать аккаунт",
              action: "",
              method: "POST",
              type: 'button'
            }
          ]
        },
      }
    }
  },
  validations() {

    let formModel = {}

    const activeForm = (this.mode == 'auth') ? this.authForm : this.regForm

    for (const field of activeForm.fields) {

      const name = field.name

      formModel[name] = {}

      if (this.mode == 'auth') {
        this.$set(this.authFormModel, name, '')
      } else {
        this.$set(this.regFormModel, name, '')
      }
      

      formModel[name].required = required

      if (field.type == 'email') {
        formModel[name].email = email
      }

      if (field.minLength) {
        formModel[name].minLength = minLength(field.minLength)
      }

      if (field.maxLength) {
        formModel[name].maxLength = maxLength(field.maxLength)
      }

    }

    return (this.mode == 'auth') ? { authFormModel: formModel } : { regFormModel: formModel }

  },
  methods: {
    setValidationError(error) {
      if (!error) {
        this.buttonClicked = false
      }
      this.validationError = error
    },
    formSubmit() {

      this.buttonClicked = true

      const form = (this.mode == 'auth') ? this.authForm : this.regForm
      const formData = (this.mode == 'auth') ? this.authFormModel : this.regFormModel
      const validationForm = (this.mode == 'auth') ? this.$v.authFormModel : this.$v.regFormModel

      if (validationForm.$invalid || this.formError) {

        this.$v.$touch()

        // print the nearest error

        for(let [key, ref] of Object.entries(this.$refs)) {
          if (ref[0].getError()) {
            this.setValidationError(ref[0].getError())
          }
        }

      } else if (!this.confirmConfidentiality) {
        this.setValidationError('Необходимо принять условия использования и политику конфиденциальности')
      } else {

        this.setValidationError('')

        this.$http.post(form.submit, formData)
          .then(response => {

            const data = response.data

            if (data.notification) {

              this.$root.showNotification(data.notification)
              this.$store.commit('setUser', data.user)

              if (data.notification.type == 'success' || data.notification.type == 'confirmEmail') {
                this.$modal.hide('signForm')
                this.$eventBus.$emit('login')
              }

            }

          })
          .catch(error => {

          })
          .then(() => {

          }) 

        console.log(formData, form)
      }
    },
    dataCheck(value, name, isClientError) {

      if (isClientError) return

      this.formError = false

      this.$refs[name][0].isSuccess = false
      this.$refs[name][0].isError = false
      this.$refs[name][0].isLoading = true

      let request = {}
      request[name] = value

      this.$http.post('/api/user/data-check', request)
        .then(response => {
          const data = response.data

          if ('errors' in data) {
            this.formError = true

            this.validationError = data.errors[0]
            this.$refs[name][0].isSuccess = false
            this.$refs[name][0].isError = true
            this.$refs[name][0].isLoading = false
          } else if ('success' in data) {
            this.$refs[name][0].isSuccess = true
            this.$refs[name][0].isError = false
            this.$refs[name][0].isLoading = false
          }

        })
    },
    changeMode(mode) {
      this.$emit('setMode', mode)
      this.setValidationError('')
    },
    authThroughSocials(social) {

      console.log(social)

    }
  }
}
</script>