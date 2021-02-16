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

    <loader 
      v-if="$isEmpty(itemsList)"
    />
    <div
      v-else
      class="item-list__tiles-wrp"
    >
      <items-categories
        :categoriesData="categoriesData"
      />
      <div class="item-list__tiles">
         <item-tile
            v-for="(item, index) in itemsList"
            :key="index"
            :name="item.name"
            :price="item.price"
            :description="item.description"
            :img="item.thumbnail"
            :likes="item.likes"
            :author="item.author"
          />
      </div>
      <loader
        v-if="!outOfItems"
      />
    </div>
  </div>
</template>

<script>
import ItemTile from '../components/ItemTile.vue'
import ItemsCategories from '../components/ItemsCategories.vue'
import Loader from '../components/Loader.vue'

import { debounce } from 'vue-debounce'

export default {
  components: {
    ItemTile,
    ItemsCategories,
    Loader,
  },
  data() {
    return {
      errorBannerShow: false,

      items: {
        data: [],
        meta: {}
      },
      categoriesData: {
        categories: []
      },
      
      page: 1,
      outOfItems: false,
      loadMoreItemsDebounced: null
    }
  },
  computed: {
    itemsList() {
      return this.items.data
    },
  },
  methods: {
    loadMoreItems(url) {
      
      this.$http.get(url)
        .then(response => {
          const data = response.data

          data.data.forEach((item) => {
            this.items.data.push(item)
          })

          for (const [key, value] of Object.entries(data)) {
            if (key == 'data') continue
            this.items.meta[key] = value
          }

          this.page++
        })

        if (this.items.meta.last_page == this.items.meta.current_page + 1) {
          this.outOfItems = true
        }
    },
    scrollCheck() {
      if (
        (window.pageYOffset + window.innerHeight >= document.querySelector(".item-list__tiles").scrollHeight + 200) &&
        !this.outOfItems &&
        !this.$store.getters.isFormOpened
      ) {
        const url = new URL(`${window.location.origin}/api/items`)
        url.searchParams.set('page', this.page)

        this.loadMoreItemsDebounced(url)
      }
    }
  },
  created() {

    if (this.$route.meta.error) {
      this.errorBannerShow = true
    }

    const url = new URL(`${window.location.origin}/api/items`)
    url.searchParams.set('page', this.page)

    this.loadMoreItems(url)
    this.loadMoreItemsDebounced = debounce(this.loadMoreItems, 100)

    this.$http.get(new URL(`${window.location.origin}/api/categories`))
    .then(response => {
      const data = response.data
      this.categoriesData.categories = data
    })

  },
  mounted() {
    window.addEventListener("scroll", this.scrollCheck)
  },
  beforeDestroy() {
    window.removeEventListener("scroll", this.scrollCheck)
  }
}
</script>