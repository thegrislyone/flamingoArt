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
  }
}
</script>