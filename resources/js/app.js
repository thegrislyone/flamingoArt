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
import VueLazyload from 'vue-lazyload'
import { createPopper } from '@popperjs/core'

//properties
import { empty, exist } from './properties/'

// filters
import { capitalize } from './filters/capitalize.filter'

//common components
import AppHeader from './components/Header/AppHeader.vue'
import AppFooter from './components/Footer/AppFooter.vue'
import Notification from './components/Notification.vue'

//components
import VModal from 'vue-js-modal'

//class prototype inicialization
Vue.prototype.$http = axios
Vue.prototype.$isEmpty = empty
Vue.prototype.$isExist = exist
Vue.prototype.$createPopper = createPopper

//filters
Vue.filter('capitalize', capitalize)

//plugins registration
Vue.use(AsyncComputed)
Vue.use(Vuelidate)
Vue.use(vueDebounce)
Vue.use(VModal)
Vue.use(vClickOutside)
Vue.use(VueCookies)
Vue.use(VueLazyload, {
    preLoad: 1.3,
    error: 'dist/error.png',
    loading: '/assets/images/loader.gif',
    attempt: 1
  })

const app = new Vue({
    el: '#app',
    components: { AppHeader, AppFooter, Notification },
    router,
    store,
    data() {
        return {
            notification: {},
            theme: this.$cookies.get('theme'),
            cookieAgreementShow: false
        }
    },
    created() {

        if (!this.theme) {
            this.$cookies.set('theme', 'dark')
            this.theme = this.$cookies.get('theme')
        }

        if (!this.$cookies.get('cookie_agreement_set')) {
            this.cookieAgreementShow = true
        }

        window.addEventListener('resize', this.onResize)

    },
    mounted() {
        document.querySelector('#app').classList.add('app_' + this.theme)
    },
    methods: {
        cookieAgreementClose() {
            this.$cookies.set('cookie_agreement_set', 1)
            this.cookieAgreementShow = false
        },
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
