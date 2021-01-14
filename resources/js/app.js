import Vue from 'vue'
import router from './router/'
import store from './store/'
import axios from 'axios'

//properties
import { empty, exist } from './properties/'

//common components
import appHeader from './components/Header/appHeader.vue'
import appFooter from './components/Footer/appFooter.vue'

//components
import VueAwesomeSwiper from 'vue-awesome-swiper'

//class prototype inicialization
Vue.prototype.$http = axios
Vue.prototype.$isEmpty = empty
Vue.prototype.$isExist = exist

//plugins registration
Vue.use(VueAwesomeSwiper)


const app = new Vue({
    el: '#app',
    components: { appHeader, appFooter },
    router,
    store,
    data() {
        return {

        }
    },
    created() {
        window.addEventListener('resize', this.onResize)
    },
    mounted() {
        document.querySelector('#app').classList.add('app_' + this.$store.getters.themeMode)
    },
    methods: {
        onResize() {
            this.$store.dispatch('windowResize')
        }
    },
    
})
