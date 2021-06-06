<template>
  <div
    class="chat-page"
    ref="chatPage"
  >

    <div
      v-if="$isEmpty(activeChat)"
      class="preloader"
    ></div>

    <template v-else>

      <div 
        v-if="mobile"
        class="chat-page__mobile-header"
      >

        <i 
          class="chat-page__back pointer"
          @click="back"
        ></i>

        <router-link 
          class="chat-page__profile-link"
          :to="'/profile/' + activeChat.user.id"
        >

          <div class="chat-page__avatar-block">
            <img 
              v-if="activeChat.user.avatar" 
              :src="activeChat.user.avatar"
              class="chat-page__avatar"
            >
            <img 
              v-else 
              src="/assets/images/unknown-user.png"
              class="chat-page__avatar"
            >
          </div>

          <div class="chat-page__nickname">
            {{ activeChat.user.login }}
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
    activeChat: {
      type: Object,
      require: true
    }
  },
  data() {
    return {

      renewMessages: null,

      messages: [],

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
  watch: {
    activeChat() {

      if (!this.$isEmpty(this.activeChat)) {

        this.setChat()

        this.getMessages(true)

      }

    }
  },
  created() {
    
    this.renewMessages = debounce(this.getMessages, 400)

  },
  methods: {
    setChat() {
      
      /* pusher init */

      Pusher.logToConsole = true;

      this.pusher = new Pusher('7e4e4873e6401ef6ec49', {
        cluster: 'eu'
      })

      this.channel = this.pusher.subscribe(this.activeChat.channel)

      this.channel.bind('message-get', data => {
        this.messages.push(data)
        setTimeout(() => {
          if (this.$refs.chatPage) {
            this.$refs.chatPage.scrollTop = this.$refs.chatPage.scrollHeight
          }
        }, 5)
      })

    },
    getMessages(init = false) {

      const url_string = window.location.origin + '/api/chat/messages/get-chat-messages'

      let url = new URL(url_string)

      url.searchParams.set('first', this.author)
      url.searchParams.set('second', this.interlocutor)
      url.searchParams.set('chat_id', this.activeChat.id)

      this.$http.get(url.href)
        .then(response => {

          const data = response.data

          this.messages = data
          
        })
        .then(() => {
          if (init) {
            this.$refs.chatPage.scrollTop = this.$refs.chatPage.scrollHeight
          }
          this.$store.dispatch('getChatsList')
        })

    },
    messageSend(messageText) {

      const request = {
        message: messageText,
        from: this.author,
        to: this.interlocutor,
        chat_id: this.activeChat.id
      }
      
      this.$http.post('/api/chat/messages/send-message', request)
        .then(response => {

          const data = response.data
          
        })

    },
    back() {
      this.$emit('back')
    }
  }
}
</script>