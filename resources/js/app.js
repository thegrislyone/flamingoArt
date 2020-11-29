import Vue from 'vue'
import router from './router/'
import store from './store/'
import axios from 'axios'
import { empty, exist } from './properties/'

Vue.prototype.$http = axios
Vue.prototype.$isEmpty = empty
Vue.prototype.$isExist = exist

const app = new Vue({
    el: '#app',
    router,
    store
});
