<template>
  <modal 
    name="delete"
    :classes="['modal', 'delete-confirmation']"
    :width="300"
    :scrollable="true"
    :height="'auto'"
    :shiftY="0.5"
    @closed="close"
  >

    <h2>Удалить “{{ item.name }}”?</h2>

    <p>После удаления работы вы не сможете её восстановить.</p>

    <div class="delete-confirmation__buttons">

      <button 
        class="delete-confirmation__button pointer"
        @click="close"
      >
        Отмена
      </button>

      <button 
        class="delete-confirmation__button pointer"
        @click="deleteItem"
      >
        Удалить
      </button>

    </div>

  </modal>
</template>

<script>
export default {
  props: {
    item: Object
  },
  mounted() {
    this.$modal.show('delete')
  },
  methods: {
    close() {
      this.$modal.hide('delete')
      this.$emit('close')
    },
    deleteItem() {
      
      const itemId = this.item.id
      const userId = this.$store.getters.user.id

      const url_string = window.location.origin + '/api/items/delete-item'
      let url = new URL(url_string)
      url.searchParams.set('item_id', itemId)
      url.searchParams.set('request_from', userId)
      
      this.$http.delete(url.href)
        .then(response => {

          const data = response.data

          if (data.success) {
            this.$router.push('/profile')
            this.$root.showNotification(data.success, 'success')
          } else if (data.errors) {
            this.$root.showNotification(data.errors[0], 'success')
          }

        })
        
    }
  }
}
</script>