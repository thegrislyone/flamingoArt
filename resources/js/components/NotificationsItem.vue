<template>
  <div class="notifications-item">

    <div class="notifications-item__img-container">

      <img 
        class="notifications-item__img"
        :src="data.item['thumbnail_item-page']"
      >

    </div>

    <div class="notifications-item__message-container">

      <div class="notifications-item__message">

        <template v-if="data.type == 'favorite'">
          Вашу работу добавили в избранное
        </template>

        <template v-else-if="data.type == 'purchase'">
          Вашу работу купили
        </template>

      </div>

      <div class="notifications-item__bottom-container">

        <div class="notifications-item__exp">
          {{ '2 часа назад' }}
        </div>

        <div
          v-if="data.type == 'purchase'"
          class="notifications-item__price"
        >
          {{ data.item.price }} ₽
        </div>

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
  created() {
    
    const url_string = window.location.origin + '/api/notifications/check-notification'
    let url = new URL(url_string)
    url.searchParams.set('notificaton_id', this.data.id)

    this.$http.get(url.href)
      .then(response => {

        const data = response.data

        if (data.status) {
          this.$emit('message-checked')
        }

      })

  }
}
</script>