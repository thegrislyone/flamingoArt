<template>
  <div class="chat">

    <div
      v-if="loading"
      class="preloader"
    ></div>

    <div
      v-else-if="$isEmpty(chatsList)"
      class="chat__empty"
    >
      Ваш список сообщений пуст
    </div>

    <template v-else>
    
      <div 
        v-if="windowWidth < 1024"
        class="chat-mobile"
      >

        <chats-list
          v-if="!interlocutor"
          class="chats-list_mobile"
          :list="chatsList"
          @chat-selected="chatSelected"
        />

        <chat-page
          v-else
          :mobile="true"
          :activeChat="activeChat"
          class="chats-page_mobile"
          @back="back"
        />

      </div>

      <div 
        v-else
        class="chat-desctop"
      >

        <chats-list
          class="chats-list_desctop"
          :list="chatsList"
          @chat-selected="chatSelected"
        />

        <div class="chats-list_desctop-line">

        </div>

        <chat-page
          :activeChat="activeChat"
          class="chats-page_desctop"
          @message-send="getChatsList"
        />

      </div>

    </template>

  </div>
</template>

<script>

import ChatsList from '../components/Chat/ChatsList.vue'
import ChatPage from '../components/Chat/ChatPage.vue'

export default {
  components: { 
    ChatsList,
    ChatPage
  },
  data() {
    return {

      loading: true,

      activeChat: {},

    }
  },
  computed: {
    chatsList() {
      return this.$store.state.chatsList
    },
    author() {
      return Number(this.$store.getters.user.id)
    },
    interlocutor() {
      return (this.$route.query.interlocutor_id) ? Number(this.$route.query.interlocutor_id) : null
    },
    windowWidth() {
      return this.$store.getters.windowWidth
    }
  },
  watch: {
    windowWidth() {
      if (this.windowWidth >= 1024 && !this.interlocutor) {
        this.chatSelected(this.chatsList[0].user.id)
      }
    }
  },
  created() {

    this.init()

  },
  methods: {
    init() {

      if (this.author == this.interlocutor) {
        this.$router.push('/')
      }
      
      this.loading = true

      if (this.interlocutor) {
        this.$http.get('/api/chat/chat-init?to=' + this.interlocutor)
          .then(() => {
            this.getChatsList(true)
          })
      } else {
        this.getChatsList(true)
      }

    },
    chatSelected(interlocutor) {
      
      this.$router.push('/chat?interlocutor_id=' + interlocutor)

      // this.activeChat = {}

      this.getActiveChat()

    },
    async getChatsList(init = false) {

      await this.$store.dispatch('getChatsList')

      if (init) {

        if ((this.interlocutor || this.windowWidth >= 1024) && !this.$isEmpty(this.chatsList)) {
          if (!this.interlocutor) {
            this.$router.push('/chat?interlocutor_id=' + this.chatsList[0].user.id)
          }
          this.getActiveChat()
        }

        this.loading = false

      }

    },
    getActiveChat() {

      const url_string = window.location.origin + '/api/chat/get-chat-channel'
      let url = new URL(url_string)
      url.searchParams.set('to', this.interlocutor)

      this.$http.get(url)
        .then(response => {

          const data = response.data

          this.activeChat = data

        })

    },
    back() {
      this.activeChat = null
      this.$router.push('/chat')
      this.getChatsList()
    }
  }
}
</script>