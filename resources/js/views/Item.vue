<template>
  <div>
    <h1>товар №{{ id }}</h1>
    <router-link to="/items_list">обратно в ленту товаров</router-link>
  </div>
</template>

<script>
export default {
  computed: {
    id() {
      return this.$route.params.id
    }
  }
}
</script>