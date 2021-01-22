<template>
  <div class="text-input">
    <input 
      :placeholder="placeholder"
    >
  </div>
</template>

<script>

export default {
  props: {
    placeholder: {
      type: String,
      default: ""
    },
    required: {
      type: Boolean,
      default: false
    }
  }
}
</script>