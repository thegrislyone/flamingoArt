<template>
  <div class="tile" @click="transition">
    <router-link :to="'/item/' + id" @click="transition">
      <div class="tile__wrp">
        <div class="tile__thumbnail">
          <img :src="img">
        </div>
        <!-- <div class="tile__inf">
          <div class="tile__author">
            <div class="tile__avatar-block">
              <img :src="img">
            </div>
            <span class="tile__author-name">
              {{ author }}
            </span>
          </div>
          <div class="tile__likes">
            {{ likes }}
          </div>
        </div> -->
      </div>
    </router-link>
    
  </div>
</template>

<script>
export default {
  components: {},
  props: {
    id: Number,
    name: String,
    price: Number,
    description: String,
    img: {
      type: String,
      defautl: "" // TODO: картинка при отсутствии thumbnail-изображения
    },
    likes: Number,
    author: Number//String
  },
  created() {
  },
  methods: {
    transition() {
      this.$http.put('/api/transition-to-item?item_id=' + this.id)
        .then(response => {
          const data = response.data
        })
    }
  }
}
</script>