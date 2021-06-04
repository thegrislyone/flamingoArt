<template>
  <div class="admin-panel">
    
    <!--<div class="admin-panel__items">

    </div>-->

    <div
      v-if="!$isEmpty(users)"
      class="admin-panel__users"
    >

      <table>
        <thead>
          <tr>
            <td 
              v-for="(item, key) in usersHat"
              :key="key"
            >
              {{ item }}
            </td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="user in users"
            :key="user.id"
          >
            <td 
              v-for="(userParameter, key) in user"
              :key="key"
            >
              {{ userParameter || 'null' }}
            </td>
            <td>
              <button v-if="!user.banned" @click="ban(user.id)">Забанить</button>
              <button v-else @click="ban(user.id)">Разбанить</button>
            </td>
          </tr>
        </tbody>
      </table>

    </div>

    <!--<div class="admin-panel__tags">

    </div>-->

  </div>
</template>

<script>
export default {
  data() {
    return {
      users: null,
      tags: null,
      items: null
    }
  },
  computed: {
    usersHat() {

      let hat = []

      for (let item in this.users[0]) {
        hat.push(item)
      }

      return hat

    }
  },
  created() {

    this.getUsers()

  },
  methods: {
    getUsers() {
      this.$http.get('/api/user/get-users')
        .then(response => {
          const data = response.data

          this.users = data
        })
    },
    ban(id) {

      this.$http.get('/api/user/ban-user?user_id=' + id)
        .then(response => {
          this.getUsers()
        })

    }
  }
}
</script>