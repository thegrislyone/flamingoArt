<template>
  <div class="text-input">
    <input :type="type" :placeholder="placeholder">
  </div>
</template>

<script>

export default {
  props: {
    placeholder: {
      type: String,
      default: ""
    },
    type: {
      type: String,
      default: "text"
    }
  }
}
</script>