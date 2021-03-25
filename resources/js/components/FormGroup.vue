<template>
  <div class="form-group">
  </div>
</template>

<script>
export default {
  props: {
    formData: {
      type: Object,
      require: true
    },
    v: {
      type: Object
    }
  },
  created() {
    console.log(this.formData, this.v)
  }
}
</script>