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
        @input="input"
        @keydown.enter="messageSend"
      >
      
      <!--<img 
        src="/assets/images/i-chat-send.svg"
        class="message-input__send-button pointer"
        @click="messageSend"
      >-->
      
      <svg 
        class="message-input__send-button pointer"
        :class="{
          'message-input__send-button_input': buttonFilled
        }"
        @click="messageSend"
        width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0.00783647 15.9855C0.00886129 16.7029 0.74259 17.1859 1.40191 16.9032L18.8565 9.41908C19.6644 9.07267 19.6644 7.92733 18.8565 7.58092L1.40191 0.0968183C0.74259 -0.185883 0.00886129 0.297092 0.00783647 1.01447L0.00125254 5.62322C0.000535762 6.12497 0.371743 6.54957 0.869088 6.61588L15 8.5L0.869087 10.3841C0.371742 10.4504 0.00053576 10.875 0.00125254 11.3768L0.00783647 15.9855Z" fill="#696969"/>
      </svg>

    </div>
  </div>
</template>

<script>

import { debounce } from 'vue-debounce'

export default {
  data() {
    return {
      message: '',
      buttonFilled: false,
      buttonUnfilledDebounced: null
    }
  },
  created() {
    this.buttonUnfilledDebounced = debounce(this.buttonUnfilled, 2000)
  },
  methods: {
    input() {
      this.buttonFilled = true
      this.buttonUnfilledDebounced()
    },
    buttonUnfilled() {
      this.buttonFilled = false
    },
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