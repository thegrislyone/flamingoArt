<template>
  <div 
    class="text-input"
  >
    <input 
      :placeholder="placeholder"
      v-model="value"
      @input="input"
    >
    <img 
      v-if="v.$error"
      class="text-input__error-icon"
      src="assets/images/required-field.png"
    >
  </div>
</template>

<script>

export default {
  props: {
    name: {
      type: String
    },
    placeholder: {
      type: String,
      default: ""
    },
    required: {
      type: Boolean,
      default: false
    },
    v: Object
  },
  data() {
    return {
      value: ""
    }
  },
  created() {
  },
  methods: {
    input() {
      this.$emit('input', this.value)
      this.$emit('input-text', this.value, this.name)
    }
  }
}
</script>