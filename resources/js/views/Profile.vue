<template>
  <div>

  <div v-if="profileLoading" class="preloader"></div>

  <div v-else class="profile">

    <div v-if="user.banner" class="profile-banner">
      <img :src="user.banner" class="profile-banner__img">
    </div>

    <div 
      class="profile-block"
      :class="{
        'profile-block_no-banner': !user.banner
      }"
    >
      <div class="profile-block__profile">
        
        <div 
          class="profile-card"
          :class="{
            'profile-card_no-banner': !user.banner
          }"
        >

          <div class="profile-card__avatar-block">
            <img :src="user.avatar || '/assets/images/unknown-user.png'">
          </div>

          <div class="profile-card__nickname">
            <span>{{ user.login }}</span>
          </div>

          <div class="profile-card__information">
            <span 
              class="profile-card__inf-wrd no-select"
              :class="{
                'profile-card__inf-wrd_opened': infOpened
              }"
              @click="infOpened = !infOpened"
            >
              Информация
              <i class="arrow"></i>
            </span>
            <div 
              class="profile-card__information-block"
              :class="{
                'profile-card__information-block_opened': infOpened
              }"
            >
              <div class="profile-card__inf-items">
                {{ items.length }} работ
              </div>
              <div class="profile-card__inf-favorite">
                {{ favorites.length }} добавили в избранное
              </div>
              <div class="profile-card__inf-social">
                <div
                  v-for="(social, key) in social"
                  :key="key"
                >
                  <img :src="social">
                </div>
              </div>
            </div>
          </div>

          <div class="profile-card__edit">

            <div class="profile-card__author-btns">

              <button 
                v-if="!isForeign"
                class="btn profile-card__edit-button profile-card__upload"
                @click="$router.push('/upload-item')"
                :disabled="user.banned"
              >
                Загрузить работу
              </button>

              <button 
                v-if="!isForeign"
                class="btn profile-card__settings"
                @click="$router.push('/profile-settings')"
                :disabled="user.banned"
              >
              </button>

            </div>
            
            <button
              v-if="isForeign && isAuthorizate"
              class="btn profile-card__edit-button profile-card__write center"
              @click="goToChat"
            >
              Написать
            </button>
          </div>

        </div>

      </div>
      <div 
        class="profile-block__my-items"
        :class="{
          'profile-block__my-items_margin': isForeign && user.banner
        }"
      >

        <div 
          v-if="!isForeign"
          class="profile-tabs"
          :class="{
            'profile-tabs_no-banner': !user.banner
          }"
        >
          <template>
            <span 
              class="profile-tab"
              :class="{
                'profile-tab_active': itemsMode == 'my-items' && $store.getters.windowWidth < 1366,
                'btn': itemsMode == 'my-items' && $store.getters.windowWidth > 1366
              }"
              @click="changeItems('my-items')"
            >Мои работы</span>
            <span 
              class="profile-tab"
              :class="{
                'profile-tab_active': itemsMode == 'favorites' && $store.getters.windowWidth < 1366,
                'btn': itemsMode == 'favorites' && $store.getters.windowWidth > 1366
              }"
              @click="changeItems('favorites')"
            >Избранное</span>
          </template>
        </div>
        
        <div v-if="itemsLoading" class="preloader"></div>

          <transition name="fade" mode="out-in">

            <items-tiles-list
              v-if="itemsList.length && itemsMode == 'my-items'"
              key="my-items"
              :tilesList="itemsList"
              :outOfItems="true"
            />

            <items-tiles-list
              v-else-if="itemsList.length && itemsMode == 'favorites'"
              key="favorites"
              :tilesList="itemsList"
              :outOfItems="true"
            />

            <div v-else-if="!itemsList.length && itemsMode == 'my-items'" key="no-items">

              <template v-if="!isForeign">
                У вас нет выложенных работ
              </template>

              <template v-else>
                У этого пользователя нет выложенных работ
              </template>

            </div>

            <div v-else-if="!itemsList.length && itemsMode == 'favorites'" key="no-favorites">У вас нет работ, добавленных в избранное</div>

          </transition>
        
      </div>
    </div>

  </div>

  </div>
</template>

<script>
import ItemsTilesList from '../components/ItemsTilesList.vue'
import Loader from '../components/Loader.vue'

export default {
  components: {
    ItemsTilesList,
    Loader
  },
  data() {
    return {
      profileLoading: true,
      itemsLoading: false,

      itemsMode: 'my-items',

      infOpened: false,
      social: [
        "/assets/images/i-vk.svg",
        "/assets/images/i-facebook.svg",
        "/assets/images/i-twitter.svg",
        "/assets/images/i-instagram.svg"
      ],

      items: [],
      favorites: [],

      userObject: null

    }
  },
  computed: {
    isAuthorizate() {
      return this.$store.getters.isAuthorizate
    },
    itemsList() {
      if (this.itemsMode == 'my-items') {
        return this.items
      } else if (this.itemsMode == 'favorites') {
        return this.favorites
      }
    },
    isForeign() {
      return !!this.$route.params.author_id
    },
    authorId() {
      return this.$route.params.author_id
    },
    user: {
      get() {
        return this.userObject
      },
      set(value) {
        this.userObject = value
      }
    }
  },
  created() {

    if (this.$route.query['mode'] != undefined && this.$route.query['mode'] == 'favorites') {
      this.itemsMode = 'favorites'
    }

    this.$eventBus.$on('login', this.login)

    this.favorites = this.$store.getters.favorites

    if (this.isForeign) {

      this.profileLoading = true

      // get my-items

      this.$http.get('/api/user/get-author?author_id=' + this.authorId)
        .then(response => {
          const data = response.data

          this.user = data
          
          this.favorites = data.favorites
          
        })
        .then(() => {
          this.profileLoading = false
        })

      // get favorites



    } else {
      this.user = this.$store.getters.user
    }

    const url = new URL(`${window.location.origin}/api/user/user-items`)

    if (this.isForeign) {
      url.searchParams.set('author_id', this.authorId)
    }
    

    this.loadMoreItems(url)
    
    this.page++

  },
  mounted() {
    if (!this.isAuthorizate) {
      this.$root.showNotification({
        title: 'Войдите в аккаунт/зарегестрируйтесь, если хотите связаться с этим пользователем',
        type: 'success'
      })
    }
  },
  methods: {
    login() {
      if (this.user.id == this.$store.getters.user.id) {
        this.$router.push('/profile')
      }
    },
    loadMoreItems(url) {

      this.itemsLoading = true
      
      this.$http.get(url)
        .then(response => {
          const data = response.data

          if (!this.$isEmpty(data)) {
            this.items = data
          }
        }).catch((error) => {
          console.log(error)
        }).then(() => {
          if (!this.isForeign) {
            this.profileLoading = false
          }
          this.itemsLoading = false
        })

    },
    changeItems(mode) {

      if (this.itemsMode == mode) {
        return
      }

      if (window.pageXOffset) {
        window.scrollTo({
          top: 0,
        })
      }

      this.itemsMode = mode

    },
    goToChat() {
      const url_string = window.location.origin + '/chat'
      let url = new URL(url_string)
      // url.searchParams.set('chat_id', this.user.chat_room)
      url.searchParams.set('interlocutor_id', this.authorId)
      this.$router.push(url.pathname + url.search)
    }
  }
}
</script>