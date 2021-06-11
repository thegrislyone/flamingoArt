<template>
  <div class="notifications">
    
    <h2 class="notifications__headline">Уведомления</h2>

    <div class="notifications__container">

      <notifications-item
        v-for="(notification, index) in notificationsList"
        :key="index"
        :data="notification"
        class="notifications__item"
        @message-checked="renewNotifications"
      />

    </div>
    
  </div>
</template>

<script>

import NotificationsItem from '../components/NotificationsItem.vue'

import { debounce } from 'vue-debounce'

export default {
  components: { NotificationsItem },
  data() {
    return {
      renewNotifications: null
    }
  },
  computed: {
    notificationsList() {
      return this.$store.state.notifications
    },
  },
  created() {
    this.renewNotifications = debounce(this.notificationsUpdate, 300)
  },
  methods: {
    notificationsUpdate() {
      this.$store.dispatch('getNotifications')
    }
  }
}
</script>