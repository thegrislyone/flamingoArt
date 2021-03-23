import Vue from 'vue'
import router from './router/'
import store from './store/'
import axios from 'axios'

//plugins
import AsyncComputed from 'vue-async-computed'
import Vuelidate from 'vuelidate'
import vueDebounce from 'vue-debounce'
import vClickOutside from 'v-click-outside'
import VueCookies from 'vue-cookies'

//properties
import { empty, exist } from './properties/'

// filters
import { capitalize } from './filters/capitalize.filter'

//common components
import AppHeader from './components/Header/AppHeader.vue'
import AppFooter from './components/Footer/AppFooter.vue'

//components
import VModal from 'vue-js-modal'

//class prototype inicialization
Vue.prototype.$http = axios
Vue.prototype.$isEmpty = empty
Vue.prototype.$isExist = exist

//filters
Vue.filter('capitalize', capitalize)

//plugins registration
Vue.use(AsyncComputed)
Vue.use(Vuelidate)
Vue.use(vueDebounce)
Vue.use(VModal)
Vue.use(vClickOutside)
Vue.use(VueCookies)

const app = new Vue({
    el: '#app',
    components: { AppHeader, AppFooter },
    router,
    store,
    data() {
        return {
            theme: this.$cookies.get('theme')
        }
    },
    created() {

        if (!this.theme) {
            this.$cookies.set('theme', 'dark')
            this.theme = this.$cookies.get('theme')
        }

        window.addEventListener('resize', this.onResize)

    },
    mounted() {
        document.querySelector('#app').classList.add('app_' + this.theme)
    },
    methods: {
        changeTheme(theme) {
            // delete old class
            document.querySelector('#app').classList.remove('app_' + this.theme)
            // set new class
            document.querySelector('#app').classList.add('app_' + theme)
            // set cookies and root variable
            this.$cookies.set('theme', theme)
            this.theme = this.$cookies.get('theme')
        },
        onResize() {
            this.$store.dispatch('windowResize')
        }
    },
    
})
