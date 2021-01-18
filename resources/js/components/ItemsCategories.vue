<template>
  <div class="categories">
    <div class="categories-wrp">
      <swiper 
        :options="swiperOptions"
      >
      <swiper-slide 
        v-for="(category, index) in categoriesData.categories"
        :key="index"
        class="category"
      >
        {{ category.name | capitalize }}
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
      type: Object,
      required: true
    }
  }, 
  data() {
    return {
      swiperOptions: {
        slidesPerView: 2,
        spaceBetween: 10,
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