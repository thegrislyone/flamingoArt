<template>
  <div class="auth-form">
    <div class="auth-form__header">
      <div class="auth-form__close" @click="$emit('form-close')"></div>
      <div class="auth-form__headline">
        <img src="../../../public/assets/images/logo-word.png" alt="">
      </div>
    </div>
    
    <div class="form">

      <h2 class="form__headline">Регистрация</h2>

      <div class="form__fields">
        <text-input
          class="form__field"
          :placeholder="'Email'"
        />
        <text-input
          class="form__field"
          :placeholder="'Имя и фамилия'"
        />
        <text-input
          class="form__field"
          :placeholder="'Имя пользователя'"
        />
        <text-input
          class="form__field"
          :placeholder="'Пароль'"
        />
        <text-input
          class="form__field"
          :placeholder="'Подтвердите пароль'"
        />
      </div>

      <div class="form__permission">
        <div>
          <input type="checkbox" id="permission-checkbox">
          <label for="permission-checkbox"></label>
        </div>
        <span>
          Я принимаю условия пользовательского соглашения
        </span>
      </div>

      <div class="form__btn-wrp">
        <button class="btn">
          Зарегестрироваться
        </button>
      </div>
      

    </div>

  </div>
</template>

<script>
import TextInput from './TextInput.vue'

export default {
  components: {
    TextInput
  }
}
</script>