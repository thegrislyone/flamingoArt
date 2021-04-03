<template>
  <div class="switch">
    <div 
      class="switch__trace"
      :class="{
        'switch__trace_selected': value
      }"
      @click="input"
    >
      <div 
        class="switch__switcher"
        :class="{
          'switch__switcher_selected': value
        }"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    input() {
      console.log(this.value)
      this.$emit('input', !this.value)
    }
  }
}
</script>