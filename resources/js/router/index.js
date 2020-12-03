import vueRouter from 'vue-router'
import Vue from 'vue'

//components
import Index from '../views/Index.vue'
import ErrorPage from '../views/ErrorPage.vue'
import Contacts from '../views/Contacts.vue'
import ItemList from '../views/ItemsList.vue'
import Item from '../views/Item.vue'

Vue.use(vueRouter)

const routes = [
  {
    path: '/',
    component: Index
  },
  {
    path: '/contact',
    component: Contacts
  },
  {
    path: '/items_list',
    component: ItemList
  },
  {
    path: '/items_list/:id',
    component: Item
  },
  {
    path: "*",
    component: ErrorPage
  }
]

export default new vueRouter({
  mode: "history",
  routes
})