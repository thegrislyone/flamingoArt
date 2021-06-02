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

      <form @submit.prevent="onSubmit">

        <div v-if="formType == 'nickname'">
          fgdgdfgdg
        </div>

      </form>
      
    </modal>

  </div>
</template>

<script>
export default {
  data() {
    return {
      formType: ''
    }
  },
  methods: {
    showModal(type) {
      
      this.formType = type

      this.$modal.show('settingsForm')

    }
  }
}
</script>