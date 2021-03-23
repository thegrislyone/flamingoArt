import VueRouter from 'vue-router'
import Vue from 'vue'
import store from '../store/index.js'

//components
import Index from '../views/Index.vue'
import Contacts from '../views/Contacts.vue'
import ItemList from '../views/ItemsList.vue'
import Profile from '../views/Profile.vue'
import Item from '../views/Item.vue'

Vue.use(VueRouter)

const routes = [
  // {
  //   path: '/',
  //   component: Index
  // },
  {
    path: '/',
    name: 'index',
    component: ItemList
  },
  {
    path: '/profile',
    meta: { requiresAuth: true },
    component: Profile,
    beforeEnter: (to, from, next) => {
      if (!store.getters.isAuthorizate) {
        next({ name: 'index' })
      } else {
        next()
      }
    }
  },
  {
    path: '/contact',
    component: Contacts
  },
  {
    path: '/items_list/:id',
    component: Item
  },
  {
    path: "*",
    meta: {
      error: true
    },
    component: ItemList
  }
]

const router = new VueRouter({
  mode: "history",
  routes
})

// router.beforeEach((to, from, next) => {
  
//   if (to.matched.some(record => record.meta.requiresAuth)) {
//     if (!store.getters.user.name) {
//       next({ name: 'index' })
//     } else {
//       next()
//     }
//   }

// })

export default router