<template>
  <div class="tile">
    <!-- <div>{{ name }}</div>
    <div>{{ price }}</div>
    <div>{{ description }}</div> -->
    <div class="tile__thumbnail-wrp">
      <img
        class="tile__thumbnail"
        :src="img"
      >
    </div>
    <div class="tile__inf">
      <div class="tile__left-side">
        <div class="tile__author-avatar">
          <img src="../../../public/assets/images/item2.jpg">
        </div>
        <div class="tile__author-name">
          {{ author }}
        </div>
      </div>
      <div class="tile__right-side">
        <div class="tile__likes">
          <img src="../../../public/assets/images/i-like.svg">
          <span>{{ likes }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  props: {
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