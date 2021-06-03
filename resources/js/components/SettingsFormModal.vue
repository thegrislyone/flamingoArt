<template>
  <div class="settings-form-modal">

    <modal
      name="settingsForm"
      :classes="['modal','sign-in-form']"
      :height="'auto'"
      :scrollable="true"
      :min-width="320"
      :adaptive="true"
      :max-width="480"
    >

      <img 
        class="sign-form__close"
        src="/assets/images/i-close.svg" 
        alt=""
      >

      <form @submit.prevent="apply">

        <div v-if="formType == 'nickname'">

          <h2 class="settings-form-modal__headline">Изменить никнейм</h2>
          
          <form-group
            :ref="loginForm.name"
            :formData="loginForm"
            :v="$v.login"
            @error="setValidationError"
            @clearErrors="setValidationError('')"
            @validateInput="dataCheck"
            v-model="$v.login.$model"
          />

          <div class="sign-form__validation-error">
            {{ validationError }}
          </div>

          <button 
            class="btn center settings-form-modal__btn"
            type="button"
            @click="apply"
          >
            Подтвердить
          </button>

        </div>

      </form>
      
    </modal>

  </div>
</template>

<script>

import FormGroup from './FormGroup.vue'
import { required, email, sameAs, minLength, maxLength } from 'vuelidate/lib/validators'

export default {
  components: { FormGroup },
  data() {
    return {

      validationError: '',

      formType: '',

      login: '',
      password: '',
      email: '',

      loginForm: {
        name: 'login',
        requireServerValidation: true,
        class: 'sign-form__field',
        type: 'text',
        placeholder: 'Логин',
        minLength: 2,
        maxLength: 32,
        value: this.$store.getters.user.login
      },
    }
  },
  computed: {
    user() {
      return this.$store.getters.user
    }
  },
  validations: {

    
    login: {
      required,
      minLength: minLength(2),
      maxLength: maxLength(32)
    },
    

  },
  created() {
    this.login = this.user.login
  },
  methods: {
    showModal(type) {
      
      this.formType = type

      this.$modal.show('settingsForm')

    },
    setValidationError(error) {
      this.validationError = error
    },
    apply() {

      if (this.formType == 'nickname') {
        
        if (this.$v.login.$invalid) {
          return
        }

        this.$http.post('/api/auth/set-user-login', {
          login: this.login
        })
          .then(response => {

            const data = response.data

            if (data.notification) {

              this.$root.showNotification(data.notification)

              this.$modal.hide('settingsForm')

              this.$store.commit('setUser', data.user)
              
            }

          })

      }

    },
    dataCheck(value, name, isClientError) {

      if (isClientError) return

      this.formError = false

      this.$refs[name].isSuccess = false
      this.$refs[name].isError = false
      this.$refs[name].isLoading = true

      let request = {}
      request[name] = value

      this.$http.post('/api/auth/data-check', request)
        .then(response => {
          const data = response.data

          if ('errors' in data && this.login != this.user.login) {
            this.formError = true

            this.validationError = data.errors[0]
            this.$refs[name].isSuccess = false
            this.$refs[name].isError = true
            this.$refs[name].isLoading = false
          } else if ('success' in data || this.login == this.user.login) {
            this.$refs[name].isSuccess = true
            this.$refs[name].isError = false
            this.$refs[name].isLoading = false
          }

        })
    }
  }
}
</script>