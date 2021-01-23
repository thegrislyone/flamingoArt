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
              :v="$v.formModel[field.name]"
              v-model="$v.formModel[field.name].$model"
            />
          </div>

          <div 
            v-if="activeForm.permission"
            class="sign-form__permission"
          >
            <div>
              <input 
                type="checkbox"
                id="permission"
              >
              <label for="permission"></label>
            </div>
            <div class="sign-form__permission-text">{{ activeForm.permission.text }}</div>
          </div>

          <div class="sign-form__button">
            <button 
              class="btn"
              @click="buttonClick"
            >{{ activeForm.buttonText | capitalize }}</button>
          </div>

          <div class="sign-form__link-block">
            <span>{{ activeForm.lowerText.text | capitalize }}</span>
            <span class="link" @click="changeFormMode(activeForm.changeKey)">{{ activeForm.lowerText.link | capitalize }}.</span>
          </div>

          <div class="sign-form__validation">
            <div
              v-for="(error, index) in errors"
              :key="index"
              class="sign-form__validation-error"
            >{{ error.text | capitalize }}</div>
          </div>
          
        </div>
      </div>

    </div>

  </div>
</template>

<script>
import TextInput from './TextInput.vue'
import { required, minLength, maxLength, sameAs, email } from 'vuelidate/lib/validators'

export default {
  components: {
    TextInput
  },
  data() {
    return {
      formModel: {},
      errors: [],
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
              name: "email",
              placeholder: "Email",
              required: true
            },
            {
              name: "password",
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
              name: "email",
              placeholder: "Email",
              required: true
            },
            {
              name: "fullName",
              placeholder: "Имя и фамилия",
              required: true
            },
            {
              name: "nickName",
              placeholder: "Имя пользователя",
              required: true
            },
            {
              name: "password",
              placeholder: "Пароль",
              required: true
            },
            {
              name: "repeatPassword",
              placeholder: "Повторите пароль",
              required: true
            }
          ],
          permission: {
            text: "Я принимаю условия пользовательского соглашения"
          }
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
  validations() {

    let formModel = {},
        validationModel = {}

    this.activeForm.fields.forEach((item, index) => {
      formModel[item.name] = ''
    })

    this.formModel = Object.assign({}, formModel)

    if (this.formMode === 'registration') {
      validationModel = {
        email: {
          required,
          email: email
        },
        fullName: {
          required,
          maxLength: maxLength(52)
        },
        nickName: {
          required,
          minLength: minLength(8),
          maxLength: maxLength(32)
        },
        password: {
          required,
          minLength: minLength(8),
          maxLength: maxLength(32)
        },
        repeatPassword: {
          required,
          minLength: minLength(8),
          maxLength: maxLength(32),
          sameAs: sameAs('password')
        }
      }
    } else if (this.formMode === 'authorization') {
      validationModel = {
        email: {
          required,
          email: email
        },
        password: {
          required,
          minLength: minLength(8),
          maxLength: maxLength(32)
        }
      }
    }

    return {
      formModel: validationModel
    }
  },
  created() {
  },
  mounted() {
  },
  methods: {
    buttonClick() {
      this.$v.$touch()
      this.errors = []
      if (this.$v.$error) {
        const validationModel = this.$v.formModel

        if (validationModel.email && !validationModel.email.required) {
          this.errors.push({
            text: "Почта обязательна для заполнения"
          })
        } else if (validationModel.email && !validationModel.email.email) {
          this.errors.push({
            text: "Неправильный формат почты"
          })
        }

        if (validationModel.password && !validationModel.password.required) {
          this.errors.push({
            text: "Пароль обязателен для заполнения"
          })
        } else if (validationModel.password && !validationModel.password.minLength) {
          this.errors.push({
            text: "Пароль должен быть не менее 8 символов"
          })
        } else if (validationModel.password && !validationModel.password.maxLength) {
          this.errors.push({
            text: "Пароль должен быть не более 32 символов"
          })
        }

        if (validationModel.repeatPassword && !validationModel.repeatPassword.required) {
          this.errors.push({
            text: "Введите пароль повторно"
          })
        } else if (validationModel.repeatPassword && !validationModel.repeatPassword.sameAs) {
          this.errors.push({
            text: "Пароли не совпадают"
          })
        }

        if (validationModel.fullName && !validationModel.fullName.required) {
          this.errors.push({
            text: "Имя и фамилия обязательны для заполнения"
          })
        } else if (validationModel.fullName && !validationModel.fullName.minLength) {
          this.errors.push({
            text: "Имя и фамилия должны быть не менее 8 символов"
          })
        } else if (validationModel.fullName && !validationModel.fullName.maxLength) {
          this.errors.push({
            text: "Имя и фамилия должны быть не более 32 символов"
          })
        }

        if (validationModel.nickname && !validationModel.nickname.required) {
          this.errors.push({
            text: "Имя пользователя обязательено для заполнения"
          })
        } else if (validationModel.nickname && !validationModel.nickname.minLength) {
          this.errors.push({
            text: "Имя пользователя должно быть не менее 8 символов"
          })
        } else if (validationModel.nickname && !validationModel.nickname.maxLength) {
          this.errors.push({
            text: "Имя пользователя должно быть не более 32 символов"
          })
        }

      } else {
        // отправка формы
      }
    },
    changeFormMode(mode) {
      this.errors = []
      this.$store.commit('setSignFormMode', mode)
    }
  }
}
</script>