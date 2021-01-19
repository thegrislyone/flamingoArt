<template>
  <div class="search">
    <input 
      type="text"
      :placeholder="placeholder"
    >
  </div>
</template>

<script>
export default {
  props: {
    placeholder: String
  }
}
</script>
