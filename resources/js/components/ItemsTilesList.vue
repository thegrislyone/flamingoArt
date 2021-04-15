<template>
  <div class="items-tiles-list">
    <div 
      v-if="!$isEmpty(tilesList)"
      class="item-list__tiles"
    >
      <item-tile
        v-for="(item, index) in tilesList"
        :key="index"
        :id="item.id"
        :name="item.name"
        :price="item.price"
        :description="item.description"
        :img="item.thumbnail"
        :likes="item.likes"
        :author="item.author"
      />
    </div>
    <div
      v-if="!outOfItems"
      class="preloader item-list__preloader"
    ></div>
  </div>
</template>

<script>
import ItemTile from './ItemTile.vue'
import Loader from './Loader.vue'

export default {
  components: {
    ItemTile,
    Loader
  },
  props: {
    tilesList: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    outOfItems: {
      type: Boolean,
      default: false
    }
  },
  created() {
  }
}
</script>