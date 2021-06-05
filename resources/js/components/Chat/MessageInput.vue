<template>
  <div 
    class="message-input-wrapper no-select"
    @click="focus"
  >
    <div class="message-input">
      <input 
        class="message-input__field"
        placeholder="Написать сообщение..."
        type="text"
        v-model="message"
        @keydown.enter="messageSend"
      >
      <img 
        src="/assets/images/i-chat-send.svg"
        class="message-input__send-button pointer"
        @click="messageSend"
      >
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: ''
    }
  },
  methods: {
    messageSend() {
      this.$emit('message-send', this.message)
      this.message = ''
    },
    focus() {
      document.querySelector('.message-input__field').focus()
    }
  }
}
</script>