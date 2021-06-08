<template>
  <div 
    v-if="data.last_message || (data.user_first == author && windowWidth >= 1024)"
    class="chat-list-item pointer"
    :class="{
      'chat-list-item_active': windowWidth >= 1024 && data.user.id == interlocutor
    }"
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
        <template v-if="data.last_message.message_text">
          <span v-if="data.last_message.from == author" class="chat-list-item__your-prfx">Вы:</span> {{ data.last_message.message_text }}
        </template>
        <template v-else>Нет сообщений</template>
      </div>
      
    </div>

    <div 
      v-if="!(data.last_message.from == author && data.last_message.checked)"
      class="chat-list-item__checked-indicator"
    >

      <img 
        v-if="data.last_message.from == author && !data.last_message.checked"
        src="/assets/images/i-chat-unchecked.svg"
      >

      <div 
        v-if="data.last_message.to == author && !data.last_message.checked"
        class="chat-list-item__new-messages"
      >
        {{ data.unreaded_messages | amountPrettify }}
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
  filters: {
    amountPrettify(value) {

      if (!value) return ''

      return (Number(value) > 9) ? '9+' : value

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