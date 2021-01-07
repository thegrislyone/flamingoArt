<template>
  <div>
    <h1>Лента товаров</h1>
    <loader 
      v-if="$isEmpty(itemsList)"
    />
    <div v-else>
      <item
        v-for="(item, index) in itemsList"
        :key="index"
        :name="item.name"
        :price="item.price"
        :description="item.description"
        :img="item.thumbnail"
      />
    </div>
  </div>
</template>

<script>
import Item from '../components/Item.vue'
import Loader from '../components/Loader.vue'

export default {
  components: {
    Item,
    Loader,
  },
  data() {
    return {
      items: {
        data: [],
        meta: {}
      }
    }
  },
  computed: {
    itemsList() {
      return this.items.data
    }
  },
  created() {

    const url = new URL(`${window.location.origin}/api/items`)
    url.searchParams.set('page', 1)

    this.$http.get(url)
    .then(response => {    
      const data = response.data

      this.items.data = data.data

      for (const [key, value] of Object.entries(data)) {
        if (key == 'data') continue
        this.items.meta[key] = value
      }

    })
    .catch(error => {
      console.log(error)
    })
  }
}
</script>