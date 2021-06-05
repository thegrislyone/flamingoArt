<template>
  <div class="chat">
    
    <div 
      v-if="windowWidth < 1024"
      class="chat-mobile"
    >

      <chats-list
        v-if="chats && !interlocutor"
        class="chats-list_mobile"
        :list="chats"
        @chat-selected="chatSelected"
      />

      <chat-page
        v-else-if="chats && activeChat"
        :mobile="true"
        :active="activeChat"
        class="chats-page_mobile"
        @back="back"
      />

    </div>

    <div 
      v-else
      class="chat-desctop"
    >

      <chats-list
        v-if="chats"
        class="chats-list_desctop"
        :list="chats"
      />

      Компьютерная

    </div>

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

      chats: null,
      activeChat: null,

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
      return (this.$route.query.interlocutor_id) ? Number(this.$route.query.interlocutor_id) : null
    },
    windowWidth() {
      return this.$store.getters.windowWidth
    }
  },
  created() {

    if (this.author == this.interlocutor) {
      this.$router.push('/')
    }

    this.getChatsList()

    if (this.interlocutor) {
      this.getChat()
    }

  },
  methods: {
    chatSelected(interlocutor) {
      
      this.$router.push('/chat?interlocutor_id=' + interlocutor)

      this.getChat()

    },
    getChatsList() {

      const url_string = window.location.origin + '/api/chat/get-user-chats'
      let url = new URL(url_string)

      this.$http.get(url.href)
        .then(response => {

          const data = response.data

          this.chats = data

        })

    },
    getChat() {

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