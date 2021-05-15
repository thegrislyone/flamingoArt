<template>
  <modal 
    name="purchase"
    :classes="['modal', 'purchase-confirmation']"
    :width="408"
    :scrollable="true"
    :height="'auto'"
    :shiftY="0.2"
    @closed="close"
  >


    <button 
      class="modal__close"
      @click="close"
    ></button>

    <div class="purchase-confirmation_block">
      <h2 class="purchase-confirmation__headline">Купить “{{ name }}”</h2>
      <p class="purchase-confirmation__paragraph">Вы собираетесь приобрести товар “{{ name }}” от автора {{ author }}. После оплаты вы получите оригиналы файлов и цифровые права на использование этого изображения.</p>
      <p class="purchase-confirmation__paragraph purchase-confirmation__paragraph_second">Покупая товар на сайте Flamingo-art.ru   вы соглашаетесь с тем, что процент от продажи берёт платформа.</p>
    
      <div class="purchase-confirmation__line"></div>

      <div class="purchase-confirmation__buy-block">
        <div class="purchase-confirmation__price">Итого: {{ price }} ₽</div>
        <button class="purchase-confirmation__buy btn">Купить</button>
      </div>
    
    </div>

  </modal>
</template>

<script>
export default {
  props: {
    author: String,
    name: String,
    price: Number
  },
  created() {
    
  },
  mounted() {
    this.$modal.show('purchase')
  },
  methods: {
    close() {
      this.$emit('close')
      this.$modal.hide('purchase')
    }
  }
}
</script>