import Vue from 'vue'
import router from './router/'
import store from './store/'
import axios from 'axios'
//properties
import { empty, exist } from './properties/'
//common components
import appHeader from './components/Header/appHeader.vue'
import appFooter from './components/Footer/appFooter.vue'

Vue.prototype.$http = axios
Vue.prototype.$isEmpty = empty
Vue.prototype.$isExist = exist

const app = new Vue({
    el: '#app',
    components: { appHeader, appFooter },
    router,
    store
});
