<template>
  <div class="search-page">

    <div v-if="searchLoading" class="preloader"></div>

    <div
      v-else-if="errors.length"
      class="search-page"
    >
      {{ errors[0] }}
    </div>

    <div v-else class="search-page__results-block">

      <search
        class="search-page__search"
      />

      <h2>Результаты поиска</h2>

      <div 
        v-if="tags.length"
        class="search-page__tags"
      >
        <tag 
          v-for="tag in tags"
          :key="tag.id"
          :tag="tag"
          :big="true"
          class="tag pointer no-select"
        />
      </div>

      <items-tiles-list
        v-if="items.length"
        :tilesList="items"
        :outOfItems="true"
      />

    </div>
    
  </div>
</template>

<script>

import ItemsTilesList from '../components/ItemsTilesList.vue'

import Search from '../components/Search.vue'
import Tag from '../components/Tag.vue'

export default {
  components: {
    ItemsTilesList,

    Search,
    Tag
  },
  data() {
    return {
      searchLoading: true,
      errors: [],

      data: null
    }
  },
  computed: {
    searchValue() {
      return this.$route.query['search-query']
    },
    tags() {
      return (this.data) ? this.data.tags : []
    },
    items() {
      return (this.data) ? this.data.items.concat(this.data.items_by_tags) : []
    }
  },
  created() {

    let url = new URL(window.location.origin + '/api/items/get-search-results')
    url.searchParams.set('search-query', this.searchValue)

    this.$http.get(url)
      .then(response => {
        const data = response.data

        if (!data.success && 'errors' in data) {
          this.errors = data.errors
        } else if (data.success) {
          this.data = data
        }

      })
      .then(() => {
        this.searchLoading = false
      })

  },
  methods: {

  }
}
</script>