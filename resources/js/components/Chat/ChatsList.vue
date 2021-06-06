<template>
  <div class="chats-list">

    <div
      v-if="$isEmpty(list)"
      class="preloader"
    ></div>

    <template v-else>

      <chat-list-item
        v-for="item in list"
        :key="item.id"
        :data="item"
        @chat-selected="chatSelected"
      />
      
    </template>
  
  </div>
</template>

<script>

import ChatListItem from './ChatListItem.vue'

export default {
  components: { ChatListItem },
  props: {
    list: {
      type: Array,
      require: true
    }
  },
  methods: {
    chatSelected(interlocutor) {
      this.$emit('chat-selected', interlocutor)
    }
  }
}
</script>