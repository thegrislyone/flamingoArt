<template>
  <div 
    class="notification"
    :class="{
      'notification_show': show
    }"
    @mouseenter="enter"
    @mouseleave="leave"
  >

    <button 
      class="notification__close pointer"
      @click="hidePopper"
    ></button>

    <img 
      v-if="data.status == 'success'"
      class="notification__status-icon"
      src="/assets/images/i-notification_success.svg"
    >

    <img 
      v-else-if="data.status == 'error'"
      class="notification__status-icon"
      src="/assets/images/i-notification_error.svg"
    >

    <img 
      v-else-if="data.status == 'confirmEmail'"
      class="notification__status-icon"
      src="/assets/images/i-mail.svg"
    >

    <span
      v-html="data.title"
      class="notification__text"
      :class="{
        'notification__text_success': data.status == 'success',
        'notification__text_error': data.status == 'error',
        'notification__text_email': data.status == 'confirmEmail'
      }"
    ></span>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object
    }
  },
  data() {
    return {
      show: false,
      timeOut: null
    }
  },
  watch: {
    show() {

      // if (this.show == true) {

      //   this.timeOut = setTimeout(() => {
      //     this.show = false
      //   }, 3000)
      // }
      
    }
  },
  mounted() {
  },
  methods: {
    showPopper() {

      this.show = true
      
    },
    hidePopper() {

      // this.show = false
      // this.$root.deleteNotification()

    },
    enter() {

      // if (this.show == true) {
      //   clearTimeout(this.timeOut)
      // }

    },
    leave() {

      // if (this.show == true) {
      //   this.timeOut = setTimeout(() => {
      //     this.show = false
      //   }, 3000)
      // }

    }
  }
}
</script>