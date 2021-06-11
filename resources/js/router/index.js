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
import Search from '../views/Search.vue'
import AdminPanel from '../views/AdminPanel.vue'
import ProfileSettings from '../views/ProfileSettings.vue'
import Chat from '../views/Chat.vue'
import Deals from '../views/Deals.vue'
import Notifications from '../views/Notifications.vue'

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
    name: 'profile',
    meta: { requiresAuth: true },
    component: Profile,
  },
  {
    path: '/profile-settings',
    name: 'profile-settings',
    meta: { requiresAuth: true },
    component: ProfileSettings,
  },
  {
    path: '/profile/:author_id',
    name: 'profile-foreign',
    component: Profile,
  },
  {
    path: '/item',
    redirect: { name: 'index' }
  },
  {
    path: '/item/:item_id',
    name: 'item',
    component: Item,
  },
  {
    path: '/contact',
    component: Contacts
  },
  {
    path: '/upload-item',
    name: 'upload-item',
    meta: { requiresAuth: true },
    component: UploadItem,
    beforeEnter: (to, from, next) => {
      if (store.getters.user.banned) {
        next({ name: 'index' })
      } else {
        next()
      }
    }
  },
  {
    path: '/search',
    name: 'search',
    component: Search
  },
  {
    path: '/admin-panel',
    name: 'admin',
    component: AdminPanel,
    beforeEnter: (to, from, next) => {
      if (store.getters.user.is_admin) {
        next()
      } else {
        next({ name: 'index' })
      }
    }
  },
  {
    path: '/chat',
    name: 'chat',
    meta: { requiresAuth: true },
    component: Chat
  },
  {
    path: '/my-deals',
    name: 'deals',
    meta: { requiresAuth: true },
    component: Deals
  },
  {
    path: '/notifications',
    name: 'notifications',
    meta: { requiresAuth: true },
    component: Notifications
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