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
          {{ past }}
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
  computed: {
    past() {

      const date = new Date(this.data.created_at)
      const now = new Date()

      if (this.$moment(date).diff(now, 'days')) {

        const days = this.$moment(date).diff(now, 'days') * -1

        return days + ' ' + this.declOfNum(days, ['день', 'дня', 'дней'])

      } else if (this.$moment(date).diff(now, 'hours')) {

        const hours = this.$moment(date).diff(now, 'hours') * -1

        return hours + ' ' + this.declOfNum(hours, ['час', 'часа', 'часов'])

      } else if (this.$moment(date).diff(now, 'minutes')) {

        const minutes = this.$moment(date).diff(now, 'minutes') * -1

        return minutes + ' ' + this.declOfNum(minutes, ['минута', 'минуты', 'минут'])

      } else if (this.$moment(date).diff(now, 'seconds')) {

        const seconds = this.$moment(date).diff(now, 'seconds') * -1

        return seconds + ' ' + this.declOfNum(seconds, ['секунда', 'секунды', 'секунд'])

      } else {
        return 'только что'
      }

    }
  },
  created() {

    console.log(this.past, "past")
    
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

  },
  methods: {
    declOfNum(number, titles) {  
      const cases = [2, 0, 1, 1, 1, 2];  
      return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
    }
  }
}
</script>