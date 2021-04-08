<template>
  <div>

  <div v-if="profileLoading" class="preloader"></div>

  <div v-else class="profile">

    <div v-if="user.banner" class="profile-banner">
      <img :src="user.banner" class="profile-banner__img">
    </div>

    <div class="profile-block">
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
            <button 
              class="btn profile-card__edit-button"
              @click="$router.push('/upload-item')"
            >
              Выложить работу
            </button>
          </div>

        </div>

      </div>
      <div class="profile-block__my-items">

        <div class="profile-tabs">
          <template>
            <span 
              class="profile-tab"
              :class="{
                'profile-tab_active': itemsMode == 'my-items' && $store.getters.windowWidth < 1024,
                'btn': itemsMode == 'my-items' && $store.getters.windowWidth > 1024
              }"
              @click="changeItems('my-items')"
            >Мои работы</span>
            <span 
              class="profile-tab"
              :class="{
                'profile-tab_active': itemsMode == 'favorite' && $store.getters.windowWidth < 1024,
                'btn': itemsMode == 'favorite' && $store.getters.windowWidth > 1024
              }"
              @click="changeItems('favorite')"
            >Избранное</span>
          </template>
        </div>
        
        <div v-if="itemsLoading" class="preloader"></div>

        <items-tiles-list
          v-else-if="items.length"
          :tilesList="items"
          :outOfItems="true"
        />

        <div v-else class="">У вас нет выложенных работ</div>
        
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
      favorites: []
    }
  },
  computed: {
    user() {
      return this.$store.getters.user
    }
  },
  created() {

    const url = new URL(`${window.location.origin}/api/user-items`)

    this.loadMoreItems(url)
    
    this.page++

  },
  methods: {
    loadMoreItems(url) {

      this.profileLoading = true
      
      this.$http.get(url)
        .then(response => {
          const data = response.data

          if (!this.$isEmpty(data)) {
            this.items = data
          }
        }).catch((error) => {
          console.log(error)
        }).then(() => {
          this.profileLoading = false
        })

    },
    changeItems(mode) {

      if (this.itemsMode == mode) {
        return
      }

      this.itemsMode = mode



    }
  }
}
</script>