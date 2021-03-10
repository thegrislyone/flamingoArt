<template>
  <div class="error-modal">
    <modal 
      name="status"
      :classes="['status-modal']"
      :draggable="draggable"
      :width="320"
      :scrollable="true"
      :height="'auto'"
      @closed="close"
    >
      <div class="error-modal__window">

        <div>
          <img 
            v-if="status === 'error'"
            src="/assets/images/error-modal.png"
            @click.prevent="close"
          >
          <img
            v-else-if="status === 'success'"
            src="/assets/images/success-modal.png"
            @click.prevent="close"
          >
        </div>
        
        <div>
          <h2>{{ mainHeadline }}</h2>
          <h4 v-if="!$isEmpty(subHeadline)">{{ subHeadline }}</h4>
        </div>

        <button
          class="btn btn_red"
          @click.prevent="close"
        >{{ buttonText }}</button>
      </div>
    </modal>
  </div>
</template>

<script>
export default {
  props: {
    mainHeadline: {
      type: String,
      default: "Ошибка"
    },
    subHeadline: {
      type: String,
      default: ''
    },
    buttonText: {
      type: String,
      default: "Ок"
    },
    status: {
      type: String,
      default: 'error'
    },
    draggable: {
      type: Boolean,
      default: false
    },
    closable: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    close() {
      if (this.closable) {
        this.$emit('close')
      }
      this.$modal.hide('status')
    }
  }
}
</script>