<template>
  <div class="item">

    <div v-if="loading" class="preloader"></div>

    <div v-else class="item__wrapper">
      <div class="item__block">
        <div class="item__thumbnail-block">
          <img 
            :src="item.thumbnail"
            class="item__thumbnail"
          >
        </div>
        <div class="item__information-block">

          <div class="item__information">
            <h1 class="item__name">{{ item.name }}</h1>

            <div class="item__description">
              {{ item.description }}
            </div>

            <div class="item__tags">
              <span 
                v-for="tag in item.tags"
                :key="tag.id"
                class="item__tag pointer no-select"
              >
                #{{ tag.name }}
              </span>
            </div>
          </div>

          <div class="item__actions">

            <div class="item__to-favorite pointer">
              В избранное
            </div>

            <div class="item__buy">
              <button class="btn">Купить</button>
              <span class="item__price">{{ item.price }} ₽</span>
            </div>

          </div>

        </div>
      </div>

      <div class="item__author-more">
        <div class="item__author">

          <img 
            v-if="item.author.avatar"
            class="item__author-avatar"
            :src="item.author.avatar"
          >
          <img 
            v-else 
            class="item__author-avatar"
            src="/assets/images/unknown-user.png"
          >

          <span class="item__author-nickname">{{ item.author.login }}</span>

          <span class="item__more-from-author">Ещё от автора:</span>

        </div>

        <div class="item__author-items swiper-container">
          <div class="item__swiper swiper-wrapper">
            <div 
              v-for="item in item.author.items"
              :key="item.id"
              class="item__author-item swiper-slide"
            >
              <router-link :to="'/item/' + item.id">
                <img class="item__author-item-img" :src="item.thumbnail" alt="">
              </router-link>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</template>

<script>

import Swiper from 'swiper'

export default {
  data() {
    return {
      loading: false,
      item: null,
      slider: null
    }
  },
  watch: {
    slidesAmount() {

      this.slider.destroy(true, true)

      this.sliderInit()
      
    }
  },
  computed: {
    id() {
      return this.$route.params.item_id
    },
    windowWidth() {
      return this.$store.getters.windowWidth
    },
    slidesAmount() {
      if (this.windowWidth < 640) return 2
      else if (this.windowWidth > 640 && this.windowWidth < 960) return 3
      else if (this.windowWidth > 960 && this.windowWidth < 1280) return 4
      else if (this.windowWidth > 1280 && this.windowWidth < 1440) return 5
      else if (this.windowWidth > 1440) return 6
    }
  },
  created() {

    this.loading = true

    this.$http.get('/api/single-item?item_id=' + this.id)
      .then(response => {
        const data = response.data

        console.log(data)

        if (data.errors) {

        } else {
          this.item = data
        }

        this.loading = false

      })
      .then(() => {
        this.sliderInit()
      })

  },
  mounted() {
  },
  methods: {
    sliderInit() {
      this.slider = new Swiper('.item__author-items', {
        direction: 'horizontal',
        spaceBetween: 16,
        slidesPerView: this.slidesAmount,
        freeMode: true,
        loop: false,
        observer: true
      })

      this.slider.init()
    }
  }
}
</script>