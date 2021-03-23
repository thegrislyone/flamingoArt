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
      @input="searchQueryDebounced"
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

      searchQueryDebounced: null
    }
  },
  created() {
    this.searchQueryDebounced = debounce(this.searchQuery, 400)
  },
  methods: {
    searchQuery() {
      // сделать адрес
      let url = new URL(window.location.origin + '/api/')
      url.searchParams.set('search', this.searchValue)

      console.log(url)
    },
    hideResults() {
      this.searchOpened = false
    }
  }
}
</script>
