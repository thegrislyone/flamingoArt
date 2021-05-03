<template>
  <div class="search">
    <input 
      type="text" 
      name="main-searh" 
      id="main-searh"
      autocomplete="off"
      placeholder="Поиск..."
      :class="{
        'search_opened': searchOpened
      }"
      v-click-outside="hideResults"
      v-model="searchValue"
      v-debounce:400ms.lock="getSearchTip"
      @keydown.enter="getSearchResults"
    >
    <transition name="fade">
      <div
        v-if="searchOpened"
        class="search__results"
      >
        <div
          v-for="(result, key) in searchResults"
          :key="key"
          class="search__result"
        >
          <span class="search__result-text">{{ result }}</span>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { debounce } from 'vue-debounce'
export default {
  props: {
    close: {
      type: Boolean,
      default: false
    },
    placeholder: String
  },
  data() {
    return {
      searchValue: '',

      searchResults: [],
      searchOpened: false,

      // searchQueryDebounced: null
    }
  },
  created() {
    // this.searchQueryDebounced = debounce(this.searchQuery, 400)
  },
  methods: {
    getSearchTip() {
      
      let url = new URL(window.location.origin + '/api/items/get-search-tips')
      url.searchParams.set('search', this.searchValue)

      this.$http.get(url)
        .then(response => {
          const data = response.data

          console.log(data)
        })

    },
    getSearchResults() {

      let url = new URL(window.location.origin + '/api/items/get-search-results')
      url.searchParams.set('search', this.searchValue)

      this.searchValue = ''

      console.log(url)

      this.$http.get(url)
        .then(response => {
          const data = response.data

          console.log(data)
        })

    },
    hideResults() {
      this.searchOpened = false
    }
  }
}
</script>
