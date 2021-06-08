<template>
  <div class="deals">

    <div
      v-if="loading"
      class="preloader"
    ></div>

    <template v-else>

      <div class="deals__mode-buttons">

        <button 
          class="deals__mode-button pointer"
          :class="{
            'deals__mode-button_active': showMode == 'purchases'
          }"
          @click="changeMode('purchases')"
        >
          Мои покупки
        </button>

        <button 
          class="deals__mode-button pointer"
          :class="{
            'deals__mode-button_active': showMode == 'sells'
          }"
          @click="changeMode('sells')"
        >
          Продажи
        </button>

      </div>


      <div class="deals__container">

        <transition name="fade" mode="out-in">

          <div 
            v-if="showMode == 'purchases'"
            class="purchases"
            key="purchases"
          >

            <deal-item
              v-for="purchase in purchases"
              :key="purchase.id"
              :mode="'purchase'"
              :data="purchase"
            />

          </div>

          <div 
            v-else-if="showMode == 'sells'"
            class="sells"
            key="sells"
          >

            <deal-item
              v-for="purchase in sells"
              :key="purchase.id"
              :mode="'sell'"
              :data="purchase"
            />

          </div>

        </transition>

      </div>

    </template>

  </div>
</template>

<script>

import DealItem from '../components/DealItem.vue'

export default {
  components: { DealItem },
  data() {
    return {

      loading: true,

      showMode: 'purchases',
      
      sells: null,
      purchases: null

    }
  },
  computed: {
    windowWidth() {
      return this.$store.getters.windowWidth
    }
  },
  created() {

    const url_string = window.location.origin + '/api/items/get-user-bought-items'
    const url = new URL(url_string)

    this.$http.get(url.href)
      .then(response => {
        
        const data = response.data

        this.sells = data.sells
        this.purchases = data.purchases

        this.loading = false

      })

  },
  methods: {
    changeMode(mode) {
      
      if (mode == this.showMode) {
        return
      }

      this.showMode = mode

    }
  }
}
</script>