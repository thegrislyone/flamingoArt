<template>
  <div 
    class="tag pointer no-select"
    :class="{
      'tag_big': big
    }"
    @click="tagSearch"
  >
    #{{ tag.name }}
  </div>
</template>

<script>
export default {
  props: {
    tag: Object,
    big: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    tagSearch() {

      this.$http.get('/api/tags/tags-popular-increase?search-query=' + this.tag.name)

      this.$router.push('/search?search-query=' + this.tag.name)
    }
  }
}
</script>