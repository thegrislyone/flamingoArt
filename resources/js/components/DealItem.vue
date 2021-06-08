<template>
  <div class="deals-item">
    
    <div class="deals-item__img-block">

      <img 
        :src="data.item.thumbnail_original"
        class="deals-item__img"
      >

    </div>

    <div class="deals-item__info-block">

      

      <template v-if="mode == 'purchase'">

        <div class="deals-item__container">

          <div class="deals-item__name">
            {{ data.item.name }}
          </div>

          <div class="deals-item__author">
            Автор: {{ data.author.login }}
          </div>

        </div>

        <div class="deals-item__justify-container">

          <div class="deals-item__price-purchase">
            {{ data.item.price }} ₽
          </div>

          <div class="deals-item__buy-date">
            {{ $moment(data.created_at).format('DD.MM.YYYY') }}
          </div>

        </div>

      </template>

      <template v-else-if="mode == 'sell'">

        <div class="deals-item__container">

          <div class="deals-item__name">
            {{ data.item.name }}
          </div>

          <div class="deals-item__author">
            Покупатель: {{ data.buyer.login }}
          </div>

        </div>

        <div class="deals-item__justify-container">

          <div class="deals-item__buy-date">
            {{ $moment(data.created_at).format('DD.MM.YYYY') }}
          </div>

          <div class="deals-item__price-sells">
            {{ data.item.price }} ₽
          </div>

        </div>

      </template>

    </div>

    <div 
      v-if="mode == 'purchase'"
      class="deals-item__download-block"
    >


      <button class="deals-item__download-button pointer">
        <a :href="'/api/items/download-item?item_id=' + data.item.id" download>
          <img src="../../../public/assets/images/i-download.svg">
        </a>
      </button>
      

    </div>

  </div>
</template>

<script>
export default {
  props: {
    mode: {
      type: String,
      require: true
    },
    data: {
      type: Object,
      require: true
    }
  },
  created() {
    console.log(this.data)
  },
  methods: {
    download() {

      const request = {
        item_id: this.data.item.id
      }

      this.$http.post('/api/items/download-item', request)
        .then(response => {

          const data = response.data

          

        })

    }
  }
}
</script>