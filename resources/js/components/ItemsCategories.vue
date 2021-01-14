<template>
  <div class="categories">
    <div class="categories-wrp">
      <swiper 
        :options="swiperOptions"
      >
      <swiper-slide 
        v-for="(category, index) in categoriesData"
        :key="index"
        class="category"
      >
        {{ category.name }}
      </swiper-slide>
      </swiper>
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
      type: Array,
      required: true
    }
  }, 
  data() {
    return {
      swiperOptions: {
        slidesPerView: 5,
        spaceBetween: 15,
        updateOnWindowResize: true
      },
    }
  },
  watch: {
    windowWidth() {
      document.querySelector('.categories').classList.remove('categories_overflowed')
      setTimeout(() => {
        document.querySelector('.categories').classList.add('categories_overflowed')
      }, 1)

      this.swiperOptions.slidesPerView = this.slidesQuantity()
    }
  },
  computed: {
    windowWidth() {
      return this.$store.getters.windowWidth
    },
  },
  mounted() {
    document.querySelector('.categories').classList.add('categories_overflowed')
  },
}
</script>