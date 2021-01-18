<template>
  <div class="item-list">
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
    </div>
  </div>
</template>

<script>
import ItemTile from '../components/ItemTile.vue'
import ItemsCategories from '../components/ItemsCategories.vue'
import Loader from '../components/Loader.vue'

export default {
  components: {
    ItemTile,
    ItemsCategories,
    Loader,
  },
  data() {
    return {
      items: {
        data: [],
        meta: {}
      },
      categoriesData: {
        categories: []
      },
      page: 1
    }
  },
  computed: {
    itemsList() {
      return this.items.data
    },
  },
  // asyncComputed: {
  //   categoriesData () {
  //     const url = new URL(`${window.location.origin}/api/categories`)
  //     return this.$http.get(url)
  //       .then(response => {
  //         return {
  //           categories: response.data
  //         }
  //       })
  //   }
  // },
  created() {

    const url = new URL(`${window.location.origin}/api/items`)
    url.searchParams.set('page', this.page)

    this.$http.get(url)
    .then(response => {    
      const data = response.data

      this.items.data = data.data

      for (const [key, value] of Object.entries(data)) {
        if (key == 'data') continue
        this.items.meta[key] = value
      }

      this.page++

    })
    .catch(error => {
      console.log(error)
    })

    this.$http.get(new URL(`${window.location.origin}/api/categories`))
    .then(response => {
      const data = response.data
      this.categoriesData.categories = data
    })
  }
}
</script>