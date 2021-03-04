import vueRouter from 'vue-router'
import Vue from 'vue'

//components
import Index from '../views/Index.vue'
import Contacts from '../views/Contacts.vue'
import ItemList from '../views/ItemsList.vue'
import Item from '../views/Item.vue'

Vue.use(vueRouter)

const routes = [
  // {
  //   path: '/',
  //   component: Index
  // },
  {
    path: '/',
    component: ItemList
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

export default new vueRouter({
  mode: "history",
  routes
})