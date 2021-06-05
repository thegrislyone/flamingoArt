<template>
  <div 
    class="message-wrapper"
    :class="{
        'message-wrapper_own': data.from == author,
        'message-wrapper_not-own': data.from == interlocutor
      }"
  >
    <div 
      class="message"
      ref="message"
      :class="{
        'message_own': data.from == author,
        'message_not-own': data.from == interlocutor
      }"
    >
      {{ data.message_text }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      require: true
    }
  },
  computed: {
    author() {
      return Number(this.$store.getters.user.id)
    },
    interlocutor() {
      return Number(this.$route.query.interlocutor_id)
    },
  },
  created() {
    if (!this.data.checked && this.data.from == this.interlocutor) {
      this.checkMessage()
    }
  },
  methods: {
    checkMessage() {

      const request = {
        message_id: this.data.id
      }
      
      this.$http.post('/api/chat/messages/check-message', request)
        .then(response => {

          const data = response.data

          this.$parent.renewMessages()

        })

    }
  }
}
</script>