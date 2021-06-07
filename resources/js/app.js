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
import VueMoment from 'vue-moment'
import VTooltip from 'v-tooltip'
import vuePusher from 'vue-pusher'
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
import TextareaAutosize from 'vue-textarea-autosize'

//class prototype inicialization
Vue.prototype.$http = axios
Vue.prototype.$isEmpty = empty
Vue.prototype.$isExist = exist
Vue.prototype.$createPopper = createPopper
Vue.prototype.$eventBus = new Vue()

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
    loading: '/assets/images/loader_70.gif',
    attempt: 1
  })
Vue.use(VueMoment)
Vue.use(VTooltip)
Vue.use(vuePusher, {
    api_key: '7e4e4873e6401ef6ec49',
    options: {
        cluster: 'eu',
        encrypted: true,
    }
})
Vue.use(TextareaAutosize)

const app = new Vue({
    el: '#app',
    components: { AppHeader, AppFooter, Notification },
    router,
    store,
    data() {
        return {

            pusher: null,
            channel: null,

            notification: {},
            theme: this.$cookies.get('theme'),
            cookieAgreementShow: false
        }
    },
    created() {

        if (!this.theme) {
            this.$cookies.set('theme', 'dark')
            this.theme = 'dark'
        }

        if (!this.$cookies.get('cookie_agreement_set')) {
            this.cookieAgreementShow = true
        }

        if (this.$store.getters.isAuthorizate) {
            this.notificationsInit()
            this.$store.dispatch('getChatsList')
        }

        window.addEventListener('resize', this.onResize)

    },
    mounted() {

        if (this.$store.getters.user.banned) {
            this.showNotification('Вы забанены', 'error')
        }

        if (window.MESSAGE) {
            this.showNotification(window.MESSAGE, 'success')
        }
        
        document.querySelector('#app').classList.add('app_' + this.theme)
    },
    methods: {
        notificationsInit() {

            Pusher.logToConsole = true;

            this.pusher = new Pusher('7e4e4873e6401ef6ec49', {
                cluster: 'eu'
            })

            this.channel = this.pusher.subscribe(this.$store.getters.user.common_notifications_channel)

            this.channel.bind('notification-get', async data => {

                console.log(data)
                
                if (data.notification.type == 'message') {

                    this.$eventBus.$emit('message-get', data.data)

                    await this.$store.dispatch('getChatsList')

                }

            })

        },
        cookieAgreementClose() {
            this.$cookies.set('cookie_agreement_set', 1, "1y")
            this.cookieAgreementShow = false
        },
        showNotification(notification) {
            this.notification = {
                title: notification.title,
                status: notification.type
            }
            this.$refs.notification.showPopper()
        },
        deleteNotification() {
            this.notification = {}
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
