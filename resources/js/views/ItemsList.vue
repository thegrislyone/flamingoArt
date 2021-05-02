<template>
  <div class="item-list">
  
    <div
      v-if="errorBannerShow"
      class="item-list__error-block"
    >
      <div class="item-list__error-close" @click.prevent="errorBannerShow = false"></div>
      <h1>Не удалось найти эту страницу</h1>
      <h2>Здесь нужно что-то написать</h2>
    </div>

    <div
      v-if="$isEmpty(itemsList)"
      class="preloader"
    ></div>

    <div
      v-else
      class="item-list__tiles-wrp"
    >
    
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

import Loader from '../components/Loader.vue'

import { debounce } from 'vue-debounce'

export default {
  components: {
    ItemsTilesList,
    ItemsCategories,

    Loader,
    ItemsTilesList,
  },
  data() {
    return {
      errorBannerShow: false,

      items: null,
      categoriesData: {
        categories: []
      },
      
      page: 1,
      feed: null,
      outOfItems: false,
      loadMoreItemsDebounced: null
    }
  },
  computed: {
    itemsList() {
      return (this.items) ? this.items.data : []
    },
  },
  created() {

    this.$eventBus.$on('feed-change', this.feedChange)

    if (this.$cookies.get('items-list-feed')) {
      this.feed = this.$cookies.get('items-list-feed')
    }

    if (this.$route.meta.error) {
      this.errorBannerShow = true
    }

    const url = new URL(`${window.location.origin}/api/items/get-items`)
    url.searchParams.set('page', this.page)

    if (this.feed) {
      url.searchParams.set('feed', this.feed)
    }

    this.loadMoreItems(url)
    this.loadMoreItemsDebounced = debounce(this.loadMoreItems, 100)

  },
  mounted() {
    window.addEventListener("scroll", this.scrollCheck)
  },
  beforeDestroy() {
    this.$eventBus.$off('feed-change')
    window.removeEventListener("scroll", this.scrollCheck)
  },
  methods: {
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