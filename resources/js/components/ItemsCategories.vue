<template>
  <div class="categories">
    <div class="categories__wrp swiper-container">
    
      <div class="categories__slider swiper-wrapper">
        <div 
          v-for="category in categoriesList"
          :key="category.id_categorie"
          class="categories__category swiper-slide"
          :style="{
            backgroundImage: 'url(' + category.background_img + ')'
          }"
        >
          <div 
            class="categories__bg"
            :style="{
              backgroundColor: category.background_color,
            }"
          ></div>
          <div class="categories__text">{{ category.name | capitalize }}</div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>

import Swiper from 'swiper'

export default {
  props: {
    categoriesData: {
      type: Object,
      required: true
    }
  }, 
  data() {
    return {
      slider: {}
    }
  },
  watch: {
    slidesAmount() {

      this.slider.destroy(true, true)

      this.slider = new Swiper('.categories__wrp', {
        direction: 'horizontal',
        spaceBetween: 15,
        slidesPerView: this.slidesAmount,
        freeMode: true,
        loop: false,
        observer: true,
      })
      
    }
  },
  computed: {
    windowWidth() {
      return this.$store.getters.windowWidth
    },
    categoriesList() {
      return this.categoriesData.categories
    },
    slidesAmount() {
      if (this.windowWidth < 500) return 2.2
      else if (this.windowWidth > 500 && this.windowWidth < 640) return 3.2
      else if (this.windowWidth > 640 && this.windowWidth < 720) return 4.2
      else if (this.windowWidth > 720 && this.windowWidth < 960) return 5.2
      else if (this.windowWidth > 960) return 6.2
    }
  },
  mounted() {

      this.slider = new Swiper('.categories__wrp', {
        direction: 'horizontal',
        spaceBetween: 15,
        slidesPerView: this.slidesAmount,
        freeMode: true,
        loop: false,
        observer: true,
      })

      this.slider.init()

  },
  methods: {
  }
}
</script>