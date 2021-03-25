<template>
  <div class="sign-form">
    <img 
      class="sign-form__close"
      src="/assets/images/i-close.svg" 
      alt=""
      @click="$modal.hide('signForm')"
    >
    <transition name="fade" mode="out-in">
      <div 
        v-if="mode == 'reg'"
        class="sign-form__reg"
        key="registration"
      >
        <h2 class="sign-form__form sign-form__headline">Регистрация</h2>
        
        <form class="sign-form__form">
          <form-group
            v-for="(field, key) in regForm.fields"
            :key="key"
            :formData="field"
            :v="$v.regForm[field.name]"
          />
        </form>

      </div>
      <div 
        v-else-if="mode == 'auth'"
        class="sign-form__form sign-form__auth"
        key="authorization"
      >
        <h2 class="sign-form__headline">Вход</h2>

        <form class="sign-form__form">
          <form-group
            v-for="(field, key) in authForm.fields"
            :key="key"
            :formData="field"
            :v="$v.authForm[field.name]"
          />
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
  data() {
    return {
      mode: 'auth',

      registrationForm: {},
      authForm: {
        fields: [
          {
            name: 'login',
            type: 'text',
            placeholder: 'Логин',
            minLength: 2,
            maxLength: 32,
          },
          {
            name: 'password',
            type: 'password',
            placeholder: 'Пароль',
            minLength: 6,
            maxLength: 54,
          }
        ]
      },
      regForm: {
        fields: [
          {
            name: 'login',
            type: 'text',
            placeholder: 'Логин',
            minLength: 2,
            maxLength: 32,
          },
          {
            name: 'email',
            type: 'email',
            placeholder: 'E-mail',
          },
          {
            name: 'password',
            type: 'password',
            placeholder: 'Пароль',
            minLength: 6,
            maxLength: 54,
          }
        ]
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

    return (this.mode == 'auth') ? { authForm: formModel } : { regForm: formModel }

  },
  created() {
    console.log(this.$v)
  },
  mounted() {
  },
  methods: {
  }
}
</script>