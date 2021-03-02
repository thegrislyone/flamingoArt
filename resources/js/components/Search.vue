<template>
  <div 
    class="search"
    :class="{
      'search_opened': !$isEmpty(searchResults)
    }"
    v-click-outside="closeSearch"
    @click="focus"
  >
    <input 
      type="text"
      id="search"
      :placeholder="placeholder || 'Поиск...'"
    >
    <div 
      v-if="close"
      class="search__close"
    >
    </div>

    <div 
      class="search__results"
      v-if="!$isEmpty(searchResults)"
    >
      <div 
        v-for="(result, index) in searchResults"
        :key="index"
        class="search__result"
      ><span class="search__result-text">{{ result }}</span></div>
    </div>
  </div>
</template>

<script>
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
      searchResults: []
    }
  },
  methods: {
    focus() {
      if (event.target.classList.contains('search__close')) {
        this.closing()
        return
      }
      document.querySelector('#search').focus()
      this.searchResults = [
        "результат 1",
        "результат 2",
        "результат 3"
      ]
    },
    closing() {
      this.$emit("search-close")
    },
    closeSearch() {
      this.searchResults = []
    }
  }
}
</script>
