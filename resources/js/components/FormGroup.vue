<template>
  <div class="form-group">
    <template v-if="formData.type  == 'password'">
      <input 
        class="form-group__password"
        :class="[formData.class, statusClass]"
        type="password"
        :placeholder="formData.placeholder"
        v-model="value"
        @input="input"
        v-debounce:400ms.lock="debounsedInput"
      >
    </template>
    <template v-else-if="formData.type == 'text' || formData.type  == 'email'">
      <input 
        class="form-group__text"
        :class="[formData.class, statusClass]"
        type="text"
        :placeholder="formData.placeholder"
        v-model="value"
        @input="input"
        v-debounce:400ms.lock="debounsedInput"
      >
    </template>
    <template v-else-if="formData.type == 'buttons'">
      <div class="justify-buttons">
        <div class="justify-buttons__left">
          <button
            v-for="(button, key) in formData.left"
            class="btn form-group__button"
            :type="button.type"
            @click="buttonClick(button.method, button.action)"
          >
            {{ button.title }}
          </button>
        </div>
        <div class="justify-buttons__right">
          <button
            v-for="(button, key) in formData.right"
            class="btn form-group__button"
            :type="button.type"
            @click="buttonClick(button.method, button.action)"
          >
            {{ button.title }}
          </button>
        </div>
      </div>
      <div class="center-buttons">
        <button
          v-for="(button, key) in formData.center"
          class="btn form-group__button"
          :type="button.type"
          @click="buttonClick(button.method, button.action)"
        >
          {{ button.title }}
        </button>
      </div>
      
    </template>
  </div>
</template>

<script>
import { debounce } from 'vue-debounce'

export default {
  props: {
    formData: {
      type: Object,
      require: true
    },
    v: {
      type: Object
    }
  },
  data() {
    return {
      value: '',

      isError: false,
      isSuccess: false,
      isLoading: false
    }
  },
  computed: {
    statusClass() {
      if (!this.formData.class) {
        return ''
      }
      if (this.isSuccess) {
        return this.formData.class + '_success'
      } else if (this.isError || this.v.$error) {
        return this.formData.class + '_error'
      } else if (this.isLoading) {
        return this.formData.class + '_loading'
      }
      return ''
    }
  },
  created() {
  },
  methods: {
    getError() {

      let message = ''

      if (this.v.required != undefined && !this.v.required) {
        message = 'Поле ' + this.formData.placeholder.toLowerCase() + ' обязательно для заполнения'
      } else if (this.v.minLength != undefined && !this.v.minLength) {
        message = 'Минимальная длина поля ' + this.formData.placeholder.toLowerCase() + ' - ' + this.formData.minLength + ' символов' 
      } else if (this.v.maxLength != undefined && !this.v.maxLength) {
        message = 'Максимальная длина поля ' + this.formData.placeholder.toLowerCase() + ' - ' + this.formData.maxLength + ' символов' 
      } else if (this.v.email != undefined && !this.v.email) {
        message = 'Неправильный формат почты'
      }

      return message
      
    },
    debounsedInput() {

      this.isLoading = false

      if (this.v.$error) {
        this.$emit('error', this.getError())  
        this.isError = true
      } else {
        this.isSuccess = true
      }

      if (this.formData.requireServerValidation) {
        this.$emit('validateInput', this.value, this.formData.name, this.v.$error)
      }

      this.$emit('debouncedInput', this.value, this.formData.name)
    },
    input() {

      this.isLoading = true

      this.isError = false
      this.isSuccess = false

      this.$emit('clearErrors')
      this.$emit('input', this.value)

    },
    buttonClick(method, action) {
      this.$emit('buttonClick', action, method)
    }
  }
}
</script>