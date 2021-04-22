<template>
  <div class="chat">
    <input type="text" v-model="message" @keydown.enter="sendMessage">
    <div v-for="message in messages" :key="message.message">
      <span v-if="message.author == author">Ваше сообщение - </span>
      <span v-else-if="message.author == interlocutor">Сообщение собеседника - </span>
      {{ message.message_text }}
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
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
    charRoomId() {
      return this.$route.query.chat_id
    }
  },
  created() {

    /* PUSHER INIT */
    
    Pusher.logToConsole = true;

    this.pusher = new Pusher('7e4e4873e6401ef6ec49', {
      cluster: 'eu'
    });

    /* CREATE CHANNEL */

    // this.channel = this.$pusher.subscribe('my-channel')

    this.channel = this.pusher.subscribe(this.charRoomId)

    /* CREATE CHANNEL EVENT LISTENER */

    this.channel.bind('message-get', data => {
      this.messages.push(data)
      console.log(data, this.messages)
    })

    /* GET MESSAGES LIST */

    this.getMessages()

  },
  methods: {
    getMessages() {

      const url_string = window.location.origin + '/api/chat/get-messages'
      
      let url = new URL(url_string)

      url.searchParams.set('author', this.author)
      url.searchParams.set('interlocutor', this.interlocutor)

      this.$http.get(url.href)
        .then(response => {
          const data = response.data

          if ('messages' in data) {
            this.messages = data.messages
          }
        })
    },
    sendMessage() {

      const request = {
        message: this.message,
        author: this.author,
        interlocutor: this.interlocutor,
        chat_room: this.charRoomId
      }
      
      this.$http.post('/api/chat/send-message', request)
        .then(response => {
          const data = response.data

          console.log(data)
        })

      this.message = ''

    }
  }
}
</script>