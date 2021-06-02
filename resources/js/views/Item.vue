<template>
  <div class="item">

    <div v-if="loading" class="preloader"></div>

    <div v-else class="item__wrapper">

      <purchase-confirmation
        v-if="purchaseConfirmationShow"
        :name="item.name"
        :author="item.author.login"
        :price="item.price"
        @close="buyClose"
      />

      <confirmation
        v-if="deletingProcess"
        :headline="`Удалить “${item.name}”?`"
        :text="'После удаления работы вы не сможете её восстановить.'"
        :resetButtonText="'Отмена'"
        :confirmButtonText="'Удалить'"
        @reset="deleteItemHide"
        @apply="deleteItem"
      />

      <div class="item__block">
        <div class="item__thumbnail-block">
          <img 
            :src="item.thumbnail"
            class="item__thumbnail"
          >
        </div>
        <div class="item__information-block">

          <div class="item__information">
            <h1 class="item__name">{{ item.name }}</h1>

            <div class="item__description">
              {{ item.description }}
            </div>

            <div class="item__tags">
              <tag 
                v-for="tag in item.tags"
                :key="tag.id"
                :tag="tag"
              />
            </div>

            <div class="item__statistics">
              <div class="item__favorites-number">{{ item.favorited }}</div>
              <div class="item__views">{{ item.transitions }}</div>
            </div>

          </div>

          <div 
            class="item__actions"
            :class="{
              'item__actions_no-favorites': !isAuthorized
            }"
          >

            <button
              v-if="isAuthorized && !isAuthor"
              class="item__to-favorite pointer no-select"
              :class="{
                'item__to-favorite_added': isInFavorite
              }"
              @click="addOrRemoveFavorite"
            >

              <template 
                v-if="isInFavorite"
              >
                В избранном
              </template>

              <template
                v-else
              >
                В избранное
              </template>

            </button>

            <button
              v-if="isAuthor || user.is_admin"
              class="item__delete pointer no-select"
              @click="deleteItemShow"
            >
              Удалить
            </button>

            <div
              class="item__buy"
              :class="{
                'item__buy_hidden': purchaseConfirmationShow
              }"
            >
              <button class="btn" @click="buy">Купить</button>
              <span class="item__price">{{ item.price }} ₽</span>
            </div>

          </div>

        </div>
      </div>

      <div 
        v-if="moreOfAuthor.length"
        class="item__author-more"
      >
        <div class="item__author">

          <router-link 
            :to="(isAuthor) ? '/profile' : '/profile/' + item.author.id"
            class="item__author-nickname-block pointer"
           >

            <img 
              v-if="item.author.avatar"
              class="item__author-avatar"
              :src="item.author.avatar"
            >
            <img 
              v-else 
              class="item__author-avatar"
              src="/assets/images/unknown-user.png"
            >

            <span class="item__author-nickname">{{ item.author.login }}</span>

          </router-link>

          <span class="item__more-from-author">Ещё от автора:</span>
          

        </div>

        <div class="item__author-items swiper-container">
          <div class="item__swiper swiper-wrapper">
            <div 
              v-for="item in moreOfAuthor"
              :key="item.id"
              class="item__author-item swiper-slide"
            >
              <router-link :to="'/item/' + item.id">
                <img class="item__author-item-img" :src="item.thumbnail" alt="">
              </router-link>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</template>

<script>

import Swiper from 'swiper'

import Tag from '../components/Tag.vue'

import PurchaseConfirmation from '../components/PurchaseConfirmation.vue'
import Confirmation from '../components/Confirmation.vue'

export default {
  components: {
    Tag,

    PurchaseConfirmation,
    Confirmation
  },
  data() {
    return {
      loading: false,
      item: null,
      slider: null,
      purchaseConfirmationShow: false,

      deletingProcess: false
    }
  },
  watch: {
    // slidesAmount() {

    //   console.log(this.slidesAmount)

    //   this.slider.destroy(true, true)

    //   this.sliderInit()
      
    // }
  },
  computed: {
    id() {
      return this.$route.params.item_id
    },
    isAuthorized() {
      return this.$store.getters.isAuthorizate
    },
    isInFavorite() {

      const favorite = this.$store.getters.favorites.find((favorite) => {
        if (favorite.id == this.item.id) {
          return true
        }
        return false
      })
      
      return !this.$isEmpty(favorite)
    },
    user() {
      return this.$store.getters.user
    },
    isAuthor() {
      return this.user.id == this.item.author.id
    },
    moreOfAuthor() {
      return this.item.author.items.filter(item => {
        if (item.id != this.item.id) {
          return true
        }
        return false
      })
    },
    windowWidth() {
      return this.$store.getters.windowWidth
    },
    // slidesAmount() {
    //   if (this.windowWidth < 640) return 2
    //   else if (this.windowWidth > 640 && this.windowWidth < 960) return 3
    //   else if (this.windowWidth > 960 && this.windowWidth < 1280) return 4
    //   else if (this.windowWidth > 1280 && this.windowWidth < 1440) return 5
    //   else if (this.windowWidth > 1440) return 6
    // }
  },
  created() {

    this.loading = true

    this.$http.get('/api/items/single-item?item_id=' + this.id)
      .then(response => {
        const data = response.data

        if (data.errors) {

        } else {
          this.item = data
        }

        this.loading = false

      })
      .then(() => {

        this.sliderInit()

      })

  },
  mounted() {
  },
  methods: {
    buy() {
      this.purchaseConfirmationShow = true
    },
    buyClose() {
      this.purchaseConfirmationShow = false
    },
    sliderInit() {

      this.slider = new Swiper('.item__author-items', {
        direction: 'horizontal',
        spaceBetween: 16,
        breakpoints: {
          0: {
            slidesPerView: 2
          },
          640: {
            slidesPerView: 3
          },
          960: {
            slidesPerView: 4
          },
          1280: {
            slidesPerView: 5
          },
          1440: {
            slidesPerView: 6
          },
        },
        freeMode: true,
        loop: false,
        observer: true
      })

      this.slider.init()
    },
    addOrRemoveFavorite() {

      if (!this.isInFavorite) {

        const itemId = this.item.id

        this.$http.get('/api/items/add-to-favorite?item_id=' + itemId)
          .then(response => {
            const data = response.data

            if (data.notification) {
              this.$root.showNotification(data.notification)
              this.$store.commit('setFavorites', data.favorites)
            }

          })

      } else {

        const itemId = this.item.id

        this.$http.get('/api/items/remove-from-favorite?item_id=' + itemId)
          .then(response => {
            const data = response.data

            if (data.notification) {
              this.$root.showNotification(data.notification)
              this.$store.commit('setFavorites', data.favorites)
            }

          })
      }

    },
    deleteItemShow() {

      this.deletingProcess = true
      
      // this.$modal.show('delete-confirmation')
      
      // const itemId = this.item.id
      // const userId = this.$store.getters.user.id

      // const url_string = window.location.origin + '/api/items/delete-item'
      // let url = new URL(url_string)
      // url.searchParams.set('item_id', itemId)
      // url.searchParams.set('request_from', userId)
      
      // this.$http.delete(url.href)
      //   .then(response => {

      //     const data = response.data

      //     if (data.success) {
      //       this.$router.push('/profile')
      //       this.$root.showNotification(data.success, 'success')
      //     } else if (data.errors) {
      //       this.$root.showNotification(data.errors[0], 'success')
      //     }

      //   })

    },
    deleteItemHide() {
      this.$modal.hide('confirm')
      this.deletingProcess = false
    },
    deleteItem() {

      const itemId = this.item.id
      const userId = this.$store.getters.user.id

      const url_string = window.location.origin + '/api/items/delete-item'
      let url = new URL(url_string)
      url.searchParams.set('item_id', itemId)
      url.searchParams.set('request_from', userId)
      
      this.$http.delete(url.href)
        .then(response => {

          const data = response.data

          if (data.notification) {
            this.$router.push('/profile')
            this.$root.showNotification(data.notification)
          }

        })

    }
  }
}
</script>