import Vue from 'vue'
import router from './router/'
import store from './store/'
import axios from 'axios'

//plugins
import AsyncComputed from 'vue-async-computed'
import Vuelidate from 'vuelidate'
import vueDebounce from 'vue-debounce'

//properties
import { empty, exist } from './properties/'

// filters
import { capitalize } from './filters/capitalize.filter'

//common components
import AppHeader from './components/Header/AppHeader.vue'
import AppFooter from './components/Footer/AppFooter.vue'

//components
import VueAwesomeSwiper from 'vue-awesome-swiper'

//class prototype inicialization
Vue.prototype.$http = axios
Vue.prototype.$isEmpty = empty
Vue.prototype.$isExist = exist

//calss filters set
Vue.filter('capitalize', capitalize)

//plugins registration
Vue.use(VueAwesomeSwiper)
Vue.use(AsyncComputed)
Vue.use(Vuelidate)
Vue.use(vueDebounce)


const app = new Vue({
    el: '#app',
    components: { AppHeader, AppFooter },
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
