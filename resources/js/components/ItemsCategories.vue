<template>
  <div class="categories">
    <div class="categories-wrp">
      <h1>Свайпер категорий временно убран</h1>
      <!-- <swiper 
        :options="swiperOptions"
        ref="swiper"
      >
      <swiper-slide 
        v-for="(category, index) in categoriesData.categories"
        :key="index"
        class="category"
      >
        {{ category.name | capitalize }}
      </swiper-slide>
      </swiper> -->
    </div>
  </div>
</template>

<script>
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper'

export default {
  components: {
    Swiper,
    SwiperSlide
  },
  props: {
    categoriesData: {
      type: Object,
      required: true
    }
  }, 
  data() {
    return {
      swiperOptions: {
        slidesPerView: null,
        spaceBetween: 10,
        updateOnWindowResize: true
      },
    }
  },
  watch: {
    // windowWidth() {
    //   console.log("fff")
    //   if (this.swiperOptions.slidesPerView != this.getSlidesAmount()) {
    //     this.swiperOptions.slidesPerView = this.getSlidesAmount()
    //     // this.$refs.swiper.destroySwiper()
    //     // this.$refs.swiper.initSwiper()
    //     this.$refs.swiper.options = this.swiperOptions
    //     this.$refs.swiper.updateSwiper()
    //   }
    // }
  },
  computed: {
    windowWidth() {
      return this.$store.getters.windowWidth
    },
  },
  created() {
    this.swiperOptions.slidesPerView = this.getSlidesAmount()
  },
  mounted() {
  },
  methods: {
    getSlidesAmount() {
      if (this.windowWidth < 500 && this.swiperOptions.slidesPerView != 2) return 2
      else if (this.windowWidth > 500 && this.windowWidth < 640 && this.swiperOptions.slidesPerView != 3) return 3
      else if (this.windowWidth > 640 && this.windowWidth < 720 && this.swiperOptions.slidesPerView != 4) return 4
      else if (this.windowWidth > 720 && this.windowWidth < 960 && this.swiperOptions.slidesPerView != 5) return 5
      else if (this.windowWidth > 960 && this.swiperOptions.slidesPerView != 6) return 6
    }
  }
}
</script>