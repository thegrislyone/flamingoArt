<template>
  <div class="chat">
    <input type="text" v-model="message" @keydown.enter="sendMessage">
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
  created() {
    
    // Pusher.logToConsole = true;

    // this.pusher = new Pusher('7e4e4873e6401ef6ec49', {
    //   cluster: 'eu'
    // });

    // create channel

    this.channel = this.$pusher.subscribe('my-channel')

    //create channel event listener

    this.channel.bind('my-event', data => {
      this.messages.push(JSON.stringify(data))
    })

  },
  methods: {
    sendMessage() {
      
      this.$http.get('/api/chat/send-message?message=' + this.message)
        .then(response => {
          const data = response.data

          console.log(data)
        })

    }
  }
}
</script>