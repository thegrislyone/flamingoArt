<template>
  <div class="search">
    <input 
      type="text" 
      name="main-searh" 
      id="main-searh"
      autocomplete="off"
      placeholder="Поиск..."
    >
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
