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

    <br><br>

    <div v-if="feedbacks">
      <table>
        <thead>
          <tr>
            <td 
              v-for="(feedbackHatItem, index) in feedbacksHat"
              :key="index"
            >{{ feedbackHatItem }}</td>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(feedback, index) in feedbacks"
            :key="index"
          >
            <td
              v-for="(item, key) in feedback"
              :key="key"
            >
              {{ item }}
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
      items: null,
      feedbacks: null
    }
  },
  computed: {
    usersHat() {

      let hat = []

      for (let item in this.users[0]) {
        hat.push(item)
      }

      return hat

    },
    feedbacksHat() {

      let hat = []

      for (let item in this.feedbacks[0]) {
        hat.push(item)
      }

      return hat

    }
  },
  created() {

    this.getUsers()

    this.getFeedbacks()

  },
  methods: {
    getUsers() {
      this.$http.get('/api/user/get-users')
        .then(response => {
          const data = response.data

          this.users = data
        })
    },
    getFeedbacks() {
      this.$http.get('/api/feedbacks/get-feedbacks')
        .then(response => {
          const data = response.data

          this.feedbacks = data
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