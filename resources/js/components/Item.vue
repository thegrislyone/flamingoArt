<template>
  <div>
    <div>{{ name }}</div>
    <div>{{ price }}</div>
    <div>{{ description }}</div>
    <div class="img"><img :src="img"></div>

    <hr>
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