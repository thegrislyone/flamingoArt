<template>
  <div>
    <div v-if="$isEmpty(itemsList)">
      Загрузка
    </div>
    <div v-else>
      <div v-for="(item, index) in itemsList" :key="index">
        <div>{{ item.name }}</div>
        <div>{{ item.price }}</div>
        <div>{{ item.description }}</div>
        <div><img :src="item.thumbnail" alt=""></div>
        <hr>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      itemsList: []
    }
  },
  created() {
    this.$http.get('/api/items?page=1')
    .then(response => {
      this.itemsList = response.data
    })
    .catch(error => {
      console.log(error)
    })
  }
}
</script>>