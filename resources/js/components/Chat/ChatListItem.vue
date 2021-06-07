<template>
  <div 
    v-if="data.last_message || (data.user_first == author && windowWidth >= 1024)"
    class="chat-list-item pointer"
    @click="openChat"
  >
      
    <div class="chat-list-item__avatar-block">
      <img 
        v-if="data.user.avatar" 
        :src="data.user.avatar"
        class="chat-list-item__avatar"
      >
      <img 
        v-else 
        src="/assets/images/unknown-user.png"
        class="chat-list-item__avatar"
      >
    </div>

    <div class="chat-list-item__info-block">

      <div class="chat-list-item__nickname">
        {{ data.user.login }}
      </div>

      <div class="chat-list-item__last-message">
        {{ data.last_message.message_text || 'Нет сообщений' }}
      </div>
      
    </div>

    <div 
      v-if="!(data.last_message.from == author && data.last_message.checked)"
      class="chat-list-item__checked-indicator"
    >

      <div 
        v-if="data.last_message.from == author && !data.last_message.checked"
        class="chat-list-item__unchecked"
      ></div>

      <div 
        v-if="data.last_message.to == author && !data.last_message.checked"
        class="chat-list-item__new-messages"
      >
        {{ data.unreaded_messages }}
      </div>

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
    windowWidth() {
      return this.$store.getters.windowWidth
    }
  },
  methods: {
    openChat() {

      // this.$router.push('/chat?interlocutor_id=' + this.data.user.id)

      this.$emit('chat-selected', this.data.user.id)

    }
  }
}
</script>