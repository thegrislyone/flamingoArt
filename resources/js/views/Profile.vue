<template>
  <div class="profile">

    <div v-if="loading" class="preloader"></div>

    <div
      v-else
      class="profile__main"
    >
      <hr class="profile__upper-line">

      <div 
        v-if="!$isEmpty(user.banner)"
        class="profile-banner"
      >
        <img
          class="profile-banner__img"
          src="/assets/images/banner.jpg"
        >
      </div>

      <div class="profile__profile">
        <div class="profile__left-block">
          <div 
            class="profile-info"
            :class="{
              'profile-info_no-banner': !$isEmpty(user.banner)
            }"
          >
            <div class="profile-info__avatar-and-nick">
              <div 
                class="profile-info__avatar"
                :class="{
                  'profile-info__avatar_no-banner': !$isEmpty(user.banner)
                }"
              >
                <img
                  class="profile-info__avatar-img"
                  :src="user.avatar || 'assets/images/unknown-user.png'"
                >
              </div>
              <div class="profile-info__nickname">
                <span>{{ user.nickname }}</span>
              </div>
            </div>

            <div class="profile__stats-block">
              <div class="profile__edit">
                <button class="btn profile__edit-button">Редактировать профиль</button>
              </div>
              <div class="profile__stats">
                <div class="profile__stat">
                  <span>Просмотры</span>
                  <span>{{ user.views }}</span>
                </div>
                <div class="profile__stat">
                  <span>Оценки</span>
                  <span>{{ user.likes }}</span>
                </div>
              </div>
              <div class="profile__registration-date">
                Дата регистрации {{ user.created_at }}
              </div>
            </div>
          </div>
        </div>
        
        <div class="profile__right-block">
          <div class="gallery">
            <div class="gallery__buttons">
              <div class="gallery__tabs">
                <button class="btn gallery__tab">Работы</button>
                <button class="btn gallery__tab gallery__tab_unactive">Избранное</button>
                <button class="btn gallery__tab gallery__tab_unactive">Оценки</button>
              </div>
              <div class="gallery__add">
                <button class="btn gallery__add-button">
                  <span class="gallery__add-short">+</span>
                  <span class="gallery__add-full">Добавить работу</span>
                </button>
              </div>
            </div>
            <div class="gallery">
              <items-tiles-list
                :tilesList="itemsList"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    

  </div>
</template>

<script>
import ItemsTilesList from '../components/ItemsTilesList'
import Loader from '../components/Loader.vue'

export default {
  components: {
    ItemsTilesList,
    Loader
  },
  data() {
    return {
      loading: true,
      items: {
        data: [],
        meta: {}
      },
    }
  },
  computed: {
    itemsList() {
      return this.items.data
    },
    user() {
      return this.$store.getters.user
    }
  },
  created() {

    setTimeout(() => {
      this.loading = false
    }, 1000)

    const url = new URL(`${window.location.origin}/api/items`)
    url.searchParams.set('page', this.page)

    this.loadMoreItems(url)
  },
  methods: {
    loadMoreItems(url) {
      
      this.$http.get(url)
        .then(response => {
          const data = response.data

          data.data.forEach((item) => {
            this.items.data.push(item)
          })

          for (const [key, value] of Object.entries(data)) {
            if (key == 'data') continue
            this.items.meta[key] = value
          }

          this.page++
        })

        if (this.items.meta.last_page == this.items.meta.current_page + 1) {
          this.outOfItems = true
        }
    },
  }
}
</script>