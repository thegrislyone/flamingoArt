<template>
  <div class="chat">
    <input type="text" v-model="message" @keydown.enter="sendMessage">
    <div v-for="message in messages" :key="message.id">
      <span v-if="message.from == author">Ваше сообщение - </span>
      <span v-else-if="message.from == interlocutor">Сообщение собеседника - </span>
      {{ message.message_text }}
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      chat: null,
      messages: [],
      message: '',
      pusher: null,
      channel: null
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
    this.getChat()

  },
  methods: {
    getChat() {

      const url_string = window.location.origin + '/api/chat/get-chat-channel'
      let url = new URL(url_string)
      url.searchParams.set('to', this.interlocutor)

      this.$http.get(url)
        .then(response => {

          const data = response.data

          this.chat = data

          this.setChat()
          
          this.getMessages()

        })
    },
    setChat() {
      
      /* pusher init */

      Pusher.logToConsole = true;

      this.pusher = new Pusher('7e4e4873e6401ef6ec49', {
        cluster: 'eu'
      })

      this.channel = this.pusher.subscribe(this.chat.channel)

      this.channel.bind('message-get', data => {
        this.messages.push(data)
      })

    },
    getMessages() {

      const url_string = window.location.origin + '/api/chat/messages/get-chat-messages'

      let url = new URL(url_string)

      url.searchParams.set('first', this.author)
      url.searchParams.set('second', this.interlocutor)
      url.searchParams.set('chat_id', this.chat.id)

      this.$http.get(url.href)
        .then(response => {

          const data = response.data

          this.messages = data
          
        })

    },
    sendMessage() {
      
      const request = {
        message: this.message,
        from: this.author,
        to: this.interlocutor,
        chat_id: this.chat.id
      }
      
      this.$http.post('/api/chat/messages/send-message', request)
        .then(response => {

          const data = response.data
          
          

        })

      this.message = ''
    }
  }
}
</script>