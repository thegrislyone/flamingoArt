import VueRouter from 'vue-router'
import Vue from 'vue'
import store from '../store/index.js'

//components
import Index from '../views/Index.vue'
import Contacts from '../views/Contacts.vue'
import ItemList from '../views/ItemsList.vue'
import Profile from '../views/Profile.vue'
import Item from '../views/Item.vue'
import UploadItem from '../views/UploadItem.vue'

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
    path: '/upload-item',
    meta: { requiresAuth: true },
    component: UploadItem
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

router.beforeEach((to, from, next) => {

  if (!!to.meta.requiresAuth) {
    if (!store.getters.isAuthorizate) {
      next({ name: 'index' })
    } else {
      next()
    }
  } else {
    next()
  }

})

export default router