<template>
  <div 
    class="tag pointer no-select"
    @click="tagSearch"
  >
    #{{ tag.name }}
  </div>
</template>

<script>
export default {
  props: {
    tag: Object
  },
  methods: {
    tagSearch() {

      this.$http.get('/api/tags/tags-popular-increase?search-query=' + this.tag.name)

      this.$router.push('/search?search-query=' + this.tag.name)
    }
  }
}
</script>