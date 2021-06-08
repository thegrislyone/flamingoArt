<template>
  <div class="purchases">
    {{ data }}
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: null
    }
  },
  created() {

    const url_string = window.location.origin + '/api/items/get-user-bought-items'
    const url = new URL(url_string)

    this.$http.get(url.href)
      .then(response => {
        
        const data = response.data

        this.data = data

      })

  }
}
</script>