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
            :v="$v.regFormModel[field.name]"
            @error="setValidationError"
            @clearErrors="setValidationError('')"
            @validateInput="dataCheck"
            v-model="$v.regFormModel[field.name].$model"
          />

          <div class="sign-form__validation-error">
            {{ validationError }}
          </div>

          <form-group
            class="sign-form__button"
            :formData="regForm.buttons"
          />

          <div class="sign-form__change-mode">
            Есть аккаунт? <span class="sign-form__link sign-form__link_pink" @click="changeMode('auth')">Войти</span>
          </div>

          <div class="sign-form__confirmation">
            Регистрируясь, вы принимаете условия<span class="sign-form__link">Пользовательского соглашения</span>
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
            :v="$v.authFormModel[field.name]"
            @error="setValidationError"
            @clearErrors="setValidationError('')"
            @validateInput="dataCheck"
            v-model="$v.authFormModel[field.name].$model"
          />

          <div class="sign-form__validation-error">
            {{ validationError }}
          </div>

          <form-group
            class="sign-form__button"
            :formData="authForm.buttons"
          />

          <div class="sign-form__change-mode">
            Нет аккаунта? <span class="sign-form__link sign-form__link_pink" @click="changeMode('reg')">Регистрация</span>
          </div>

          <div class="sign-form__reset-password">
            <span class="sign-form__link">Забыли пароль?</span>
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

      validationError: '',
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
              type: 'submit'
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
              type: 'submit'
            }
          ]
        },
      }
    }
  },
  computed: {

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
  created() {
  },
  mounted() {
  },
  methods: {
    setValidationError(error) {
      this.validationError = error
    },
    formSubmit() {

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

      } else {

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

      this.$http.post('/api/auth/data-check', request)
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
    }
  }
}
</script>