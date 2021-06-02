<template>
  <modal 
    name="confirm"
    :classes="['modal', 'delete-confirmation']"
    :width="300"
    :scrollable="true"
    :height="'auto'"
    @closed="close"
  >

    <h2>{{ headline }}</h2>

    <p>{{ text }}</p>

    <div class="delete-confirmation__buttons">

      <button 
        class="delete-confirmation__button pointer"
        @click="clickReset"
      >
        {{ resetButtonText }}
      </button>

      <button 
        class="delete-confirmation__button pointer"
        @click="clickConfirm"
      >
        {{ confirmButtonText }}
      </button>

    </div>

  </modal>
</template>

<script>
export default {
  props: {
    headline: String,
    text: String,
    resetButtonText: String,
    confirmButtonText: String
  },
  mounted() {
    this.$modal.show('confirm')
  },
  methods: {
    close() {
      this.$modal.hide('confirm')
      this.$emit('reset')
    },
    clickReset() {

      this.$emit('reset')

    },
    clickConfirm() {

      this.$emit('apply')
        
    }
  }
}
</script>