<template>
  <div class="item">
    
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false
    }
  },
  computed: {
    id() {
      return this.$route.params.item_id
    }
  },
  created() {

    this.loading = true

    this.$http.get('/api/single-item?item_id=' + this.id)

  },
  methods: {

  }
}
</script>