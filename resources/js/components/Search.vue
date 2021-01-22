<template>
  <div 
    class="search"
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
  methods: {
    focus() {
      if (event.target.classList.contains('search__close')) {
        this.closing()
        return
      }
      document.querySelector('#search').focus()
    },
    closing() {
      this.$emit("search-close")
    }
  }
}
</script>
