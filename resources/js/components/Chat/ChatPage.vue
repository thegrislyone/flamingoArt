<template>
  <div
    class="chat-page"
    ref="chatPage"
  >

    <template>

      <div class="chat-page__mobile-header">

        <i 
          class="chat-page__back pointer"
          @click="back"
        ></i>

        <router-link 
          class="chat-page__profile-link"
          :to="'/profile/' + active.user.id"
        >

          <div class="chat-page__avatar-block">
            <img 
              v-if="active.user.avatar" 
              :src="active.user.avatar"
              class="chat-page__avatar"
            >
            <img 
              v-else 
              src="/assets/images/unknown-user.png"
              class="chat-page__avatar"
            >
          </div>

          <div class="chat-page__nickname">
            {{ active.user.login }}
          </div>

        </router-link>

      </div>
      
      <div class="chat-page__messages">
        <message
          v-for="message in messages"
          :key="message.id"
          :data="message"
          class="chat-page__message"
        />
      </div>

      <message-input
        @message-send="messageSend"
      />

    </template>

    

  </div>
</template>

<script>

import { debounce } from 'vue-debounce'

import Message from './Message.vue'
import MessageInput from './MessageInput.vue'

export default {
  components: { Message, MessageInput },
  props: {
    mobile: {
      type: Boolean,
      default: false
    },
    active: {
      type: Object,
      require: true
    }
  },
  data() {
    return {

      renewMessages: null,

      chat: null,
      chats: null,

      messages: [],

      messageText: '',

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
    windowWidth() {
      return this.$store.getters.windowWidth
    },
  },
  created() {
    
    this.renewMessages = debounce(this.getMessages, 400)

    this.chat = this.active

    this.setChat()

    this.getMessages(true)

    // this.getChat()

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
          
          this.getMessages(true)

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
        setTimeout(() => {
          this.$refs.chatPage.scrollTop = this.$refs.chatPage.scrollHeight
        }, 5)
      })

    },
    getMessages(init = false) {

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
        .then(() => {
          if (init) {
            this.$refs.chatPage.scrollTop = this.$refs.chatPage.scrollHeight
          }
        })

    },
    messageSend(messageText) {

      const request = {
        message: messageText,
        from: this.author,
        to: this.interlocutor,
        chat_id: this.chat.id
      }
      
      this.$http.post('/api/chat/messages/send-message', request)
        .then(response => {

          const data = response.data
          

        })

      this.message = ''

    },
    back() {
      this.$emit('back')
    }
  }
}
</script>