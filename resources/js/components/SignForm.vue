<template>
  <div class="sign-form">

    <status-modal
      :mainHeadline="statusModal.headline"
      :status="statusModal.status"
      :closable="statusModal.closable"
      @close="$emit('form-close')"
    />

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
            <div 
              v-for="(field, index) in activeForm.fields"
              :key="index"
              class="sign-form__field"
            >
              <text-input
                v-if="field.type === 'text'"
                :placeholder="field.caption"
                :required="field.required"
                :name="index"
                :v="$v.formModel[index]"
                @input-text="fieldInput"
                v-model="$v.formModel[index].$model"
              />
              <password-input
                v-if="field.type === 'password'"
                :placeholder="field.caption"
                :required="field.required"
                :name="index"
                :v="$v.formModel[index]"
                @input-text="fieldInput"
                v-model="$v.formModel[index].$model"
              />
            </div>
            
          </div>

          <div 
            v-if="activeForm.permission"
            class="sign-form__permission"
          >
            <div>
              <input 
                type="checkbox"
                id="permission"
                v-model="userPermission"
              >
              <label for="permission"></label>
            </div>
            <div class="sign-form__permission-text">{{ activeForm.permission.text }}</div>
          </div>

          <div class="sign-form__button">
            <button 
              class="btn"
              :disabled="$v.$invalid || !userPermission"
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
import PasswordInput from './PasswordInput.vue'

import StatusModal from './StatusModal.vue'

import { required, minLength, maxLength, sameAs, email } from 'vuelidate/lib/validators'


export default {
  components: {
    TextInput,
    StatusModal,
    PasswordInput
  },
  data() {
    return {

      userPermission: false,

      formModel: {},
      errors: [],
      statusModal: {
        headline: '',
        status: '',
        closable: true
      },

      // TODO: закинуть на апи адрес, чтобы не грузить если не надо

      form: {
        authForm: {
          headline: "вход",
          changeKey: "registration",
          buttonText: "войти",
          sendApi: "/api/auth/login",
          lowerText: {
            text: "нет аккаунта?",
            link: "регистрация"
          },
          fields: {
            email: {
              type: 'text',
              caption: "Email",
              required: true
            },
            password: {
              type: 'password',
              caption: "Пароль",
              min_length: 8,
              required: true
            }
          }
        },
        registrationForm: {
          headline: "регистрация",
          changeKey: "authorization",
          buttonText: "зарегестрироваться",
          sendApi: "/api/auth/register",
          lowerText: {
            text: "есть аккаунт?",
            link: "войти"
          },
          fields: {
            email: {
              type: 'text',
              caption: "Email",
              required: true
            },
            name: {
              type: 'text',
              caption: "Имя и фамилия",
              min_length: 8,
              max_length: 32,
              required: true
            },
            nickname: {
              type: 'text',
              caption: "Имя пользователя",
              min_length: 6,
              max_length: 32,
              required: true
            },
            password: {
              type: 'password',
              caption: "Пароль",
              min_length: 8,
              max_length: 32,
              required: true
            },
            password_confirmation: {
              type: 'password',
              caption: "Повторите пароль",
              min_length: 8,
              max_length: 32,
              required: true,
              same_as: 'password'
            }
          },
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
        this.userPermission = false
        return this.form.registrationForm
      } else if (this.formMode === 'authorization') {
        this.userPermission = true
        return this.form.authForm
      }
    },
  },
  validations() {

    let formModel = {},
        validationModel = {}

    for (const [index, item] of Object.entries(this.activeForm.fields)) {
      formModel[index] = ''
    }

    this.formModel = Object.assign({}, formModel)

    // TODO: динамическая сборка объекта валидации

    if (this.formMode === 'registration') {
      validationModel = {
        email: {
          required,
          email: email
        },
        name: {
          required,
          maxLength: maxLength(52)
        },
        nickname: {
          required,
          minLength: minLength(6),
          maxLength: maxLength(32)
        },
        password: {
          required,
          minLength: minLength(8),
          maxLength: maxLength(32)
        },
        password_confirmation : {
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
        this.formValidation()
      } else {
        this.formSend()
      }
      
    },
    formSend() {
      // TODO: переделать на пост, который какого-то хера пустой
      let url = new URL(window.location.origin + this.activeForm.sendApi)

      // for (const [key, item] of Object.entries(this.formModel)) {
      //   url.searchParams.set(key, item)
      // }

      this.$http.post(url, this.formModel)
        .then(response => {

          const data = response.data

          if (data.hasOwnProperty('errors') || data.hasOwnProperty('email')) {
            this.statusModal.headline = (data.hasOwnProperty('errors')) ? data.errors[0] : data.email[0]
            this.statusModal.status = 'error'
            this.statusModal.closable = false
          } else if (data.hasOwnProperty('success')) {
            this.statusModal.headline = data.success
            this.statusModal.status = 'success'
            this.statusModal.closable = true
          }

          this.$store.commit('setUser', data.user)

          this.$modal.show('status')

        })
    },
    formValidation(validationModel, key) {
        
      if (validationModel.required !== undefined && !validationModel.required) {

        return `Поле ${this.activeForm.fields[key].caption.toLowerCase()} обязательно для заполнения`

      } else if (validationModel.email !== undefined && !validationModel.email) {

        return 'Неправильный формат почты'

      } else if (validationModel.minLength !== undefined && !validationModel.minLength) {

        return `Минимальная длина поля ${this.activeForm.fields[key].caption.toLowerCase()} - ${this.activeForm.fields[key].min_length} символов`
      
      } else if (validationModel.maxLength !== undefined && !validationModel.maxLength) {

        return `Максимальная длина поля ${this.activeForm.fields[key].caption.toLowerCase()} - ${this.activeForm.fields[key].max_length} символов`
      
      } else if (validationModel.sameAs !== undefined && !validationModel.sameAs) {
        
        return `Пароли не совпадают`

      } else {
        return false
      }

      // const validationModel = this.$v.formModel

      // for (const [key, field] of Object.entries(this.formModel)) {

      //   if (this.errors.length == 2) {
      //     return
      //   }
        
      //   if (validationModel[key].required !== undefined && !validationModel[key].required) {
      //     this.errors.push({
      //       text: `Поле ${this.activeForm.fields[key].caption} обязательно для заполнения`
      //     })
      //   } else if (validationModel[key].email !== undefined && !validationModel[key].email) {
      //     this.errors.push({
      //       text: 'Неправильный формат почты'
      //     })
      //   } else if (validationModel[key].minLength !== undefined && !validationModel[key].minLength) {
      //     this.errors.push({
      //       text: `Минимальная длина поля ${this.activeForm.fields[key].caption} - ${this.activeForm.fields[key].min_length} символов`
      //     })
      //   } else if (validationModel[key].maxLength !== undefined && !validationModel[key].maxLength) {
      //     this.errors.push({
      //       text: `Максимальная длина поля ${this.activeForm.fields[key].caption} - ${this.activeForm.fields[key].max_length} символов`
      //     })
      //   } else if (validationModel[key].sameAs !== undefined && !validationModel[key].sameAs) {
      //     this.errors.push({
      //       text: `Пароли не совпадают`
      //     })
      //   }

      // }
    },
    changeFormMode(mode) {
      this.errors = []
      this.$store.commit('setSignFormMode', mode)
    },
    fieldInput(value, key) {
      this.errors.pop()
      if (this.formValidation(this.$v.formModel[key], key)) {
        this.errors.push({
          text: this.formValidation(this.$v.formModel[key], key)
        })
      }
    }
  }
}
</script>