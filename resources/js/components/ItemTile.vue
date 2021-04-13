<template>
  <div class="tile">
    <router-link :to="'/item/' + id">
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
  }
}
</script>