<template>
  <div class="item-list">
  
    <!-- <div
      v-if="errorBannerShow"
      class="item-list__error-block"
    >
      <div class="item-list__error-close" @click.prevent="errorBannerShow = false"></div>
      <h1>Не удалось найти эту страницу</h1>
      <h2>Здесь нужно что-то написать</h2>
    </div> -->

    <div
      v-if="!listLoaded"
      class="preloader"
    ></div>

    <div
      v-else
      class="item-list__tiles-wrp"
    >

      <div class="popular-tags">

        <h2 class="popular-tags__headline">Популярные теги</h2>

        <div class="popular-tags__block swiper-container">

          <div class="popular-tags__wrapper swiper-wrapper">

            <tag 
              v-for="tag in popularTags"
              :key="tag.id"
              :tag="tag"
              :big="true"
              class="swiper-slide"
            />
            
          </div>

        </div>
        
      </div>
    
      <!-- <items-categories
        :categoriesData="categoriesData"
      /> -->
      
      <items-tiles-list
        :tilesList="itemsList"
        :outOfItems="outOfItems"
      />

    </div>
  </div>
</template>

<script>

import ItemsTilesList from '../components/ItemsTilesList.vue'
import ItemsCategories from '../components/ItemsCategories.vue'

import Tag from '../components/Tag.vue'

import Loader from '../components/Loader.vue'

import { debounce } from 'vue-debounce'
import Swiper from 'swiper'

export default {
  components: {
    ItemsTilesList,
    ItemsCategories,

    Tag,

    Loader
  },
  data() {
    return {

      popularTags: null,
      tagsSlider: null,

      items: [],
      outOfItems: false,
      loadMoreItemsDebounced: null,
      listLoaded: false,
      
      page: 2,

      feed: null,
    
    }
  },
  computed: {
    itemsList() {
      return (this.items) ? this.items.data : []
    },
  },
  created() {

    this.$eventBus.$on('feed-change', this.feedChange)
    this.loadMoreItemsDebounced = debounce(this.loadMoreItems, 100)

    if (this.$cookies.get('items-list-feed')) {
      this.feed = this.$cookies.get('items-list-feed')
    }

    // if (this.$route.meta.error) {
    //   this.errorBannerShow = true
    // }

    const url = new URL(`${window.location.origin}/api/items/get-items`)
    url.searchParams.set('page', 1)

    if (this.feed) {
      url.searchParams.set('feed', this.feed)
    }

    this.$http.get(url)
      .then(response => {
        const data = response.data

        if ('data' in data) {
          this.items = data
        }

      })
      .catch(error => {
        console.log(error)
      })
      .then(() => {

        this.$http.get('/api/tags/get-popular-tags?amount=' + 30)
          .then(response => {
            const data = response.data

            if (data.length) {
              this.popularTags = data
            }

            this.listLoaded = true
            
          })
          .then(() => {
            this.tagsSliderInit()
          })

      })
    
  },
  mounted() {
    window.addEventListener("scroll", this.scrollCheck)
  },
  beforeDestroy() {
    this.$eventBus.$off('feed-change')
    window.removeEventListener("scroll", this.scrollCheck)
  },
  methods: {
    tagsSliderInit() {

      this.tagsSlider = new Swiper('.popular-tags__block', {
        direction: 'horizontal',
        spaceBetween: 16,
        slidesPerView: 'auto',
        freeMode: true,
        loop: false
      })

      this.tagsSlider.init()

    },
    feedChange(feed) {

      this.items.data = []
      this.page = 1
      this.feed = feed

      const url = new URL(`${window.location.origin}/api/items/get-items`)
      url.searchParams.set('page', this.page)
      url.searchParams.set('feed', this.feed)
      
      this.$http.get(url)
        .then(response => {
          const data = response.data

          if ('data' in data && 'meta' in data) {
            this.items = data
          }

          this.page++
        })
        .then(() => {
          if (this.items.meta.last_page <= this.items.meta.current_page + 1) {
            this.outOfItems = true
          }
        })

    },
    loadMoreItems(url) {
      
      this.$http.get(url)
        .then(response => {
          const data = response.data

          if ('data' in data && 'meta' in data) {

            if (this.$isEmpty(data.data) || data.data.length < 30) {
              this.outOfItems = true
              if (this.$isEmpty(data.data)) {
                return
              }
            }

            if (this.items) {
              this.items.data.concat(data.data)
              this.items.meta = data.meta
            } else {
              this.items = data
            }
          }

          this.page++
        })
        .then(() => {
          if (this.items.meta.last_page <= this.items.meta.current_page + 1) {
            this.outOfItems = true
          }
        })
        
    },
    scrollCheck() {
      if (
        (document.querySelector(".item-list__tiles")) &&
        (window.pageYOffset + window.innerHeight >= document.querySelector(".item-list__tiles").scrollHeight) &&
        !this.outOfItems
      ) {
        const url = new URL(`${window.location.origin}/api/items/get-items`)
        url.searchParams.set('page', this.page)

        if (this.feed) {
          url.searchParams.set('feed', this.feed)
        }

        this.loadMoreItemsDebounced(url)
      }
    }
  },
}
</script>