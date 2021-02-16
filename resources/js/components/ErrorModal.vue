<template>
  <div class="error-modal">
    <modal 
      name="error"
      :classes="['status-modal']"
      :draggable="draggable"
      :width="320"
      :scrollable="true"
      :height="'auto'"
    >
      <div class="error-modal__window">

        <div>
          <img 
            src="/assets/images/error-modal.png"
            @click.prevent="$modal.hide('error')"
          >
        </div>
        
        <div>
          <h2>{{ mainHeadline }}</h2>
          <h4 v-if="!$isEmpty(subHeadline)">{{ subHeadline }}</h4>
        </div>

        <button
          class="btn btn_red"
          @click.prevent="$modal.hide('error')"
        >{{ buttonText }}</button>
      </div>
    </modal>
  </div>
</template>

<script>
export default {
  props: {
    mainHeadline: {
      type: String,
      default: "Ошибка"
    },
    subHeadline: {
      type: String,
      default: ''
    },
    buttonText: {
      type: String,
      default: "Ок"
    },
    img: {
      type: String,
      default: ""
    },
    draggable: {
      type: Boolean,
      default: false
    }
  }
}
</script>