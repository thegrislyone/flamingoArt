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

              <div 
                v-if="user.vkontakte || user.twitter || user.facebook || user.instagram"
                class="profile-card__inf-social"
              >
                
                <a v-if="user.vkontakte" :href="user.vkontakte">
                  <svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.2804 1.02984C19.414 0.580625 19.2804 0.25 18.6376 0.25H16.5107C15.969 0.25 15.7198 0.533906 15.5862 0.850156C15.5862 0.850156 14.5029 3.47719 12.9718 5.18062C12.477 5.67297 12.2495 5.83109 11.9787 5.83109C11.8451 5.83109 11.6393 5.67297 11.6393 5.22375V1.02984C11.6393 0.490781 11.4876 0.25 11.0398 0.25H7.69593C7.35648 0.25 7.15426 0.501563 7.15426 0.735156C7.15426 1.24547 7.91982 1.36406 7.99926 2.80156V5.92094C7.99926 6.60375 7.87648 6.72953 7.60565 6.72953C6.88343 6.72953 5.12843 4.09172 4.08843 1.07297C3.87898 0.487188 3.67315 0.25 3.12787 0.25H1.00093C0.394262 0.25 0.271484 0.533906 0.271484 0.850156C0.271484 1.41078 0.993707 4.19594 3.63343 7.87594C5.39204 10.388 7.86926 11.75 10.1226 11.75C11.4768 11.75 11.6429 11.4481 11.6429 10.927C11.6429 8.52641 11.5201 8.3 12.199 8.3C12.5132 8.3 13.0548 8.45813 14.3187 9.66922C15.7632 11.1067 16.0015 11.75 16.8104 11.75H18.9373C19.544 11.75 19.8509 11.4481 19.674 10.8516C19.2695 9.59734 16.5359 7.01703 16.4132 6.84453C16.099 6.44203 16.1893 6.26234 16.4132 5.90297C16.4168 5.89937 19.0132 2.2625 19.2804 1.02984Z" fill="#696969"/>
                  </svg>
                </a>

                <a v-if="user.facebook" :href="user.facebook">
                  <svg width="8" height="16" viewBox="0 0 8 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.47575 9L7.8906 6.10437H5.29677V4.22531C5.29677 3.43313 5.65911 2.66094 6.8208 2.66094H8V0.195625C8 0.195625 6.92991 0 5.90679 0C3.7707 0 2.37444 1.38688 2.37444 3.8975V6.10437H0V9H2.37444V16H5.29677V9H7.47575Z" fill="#696969"/>
                  </svg>
                </a>

                <a v-if="user.twitter" :href="user.twitter">
                  <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.1498 3.48905C16.1612 3.64217 16.1612 3.79532 16.1612 3.94844C16.1612 8.61873 12.4493 14 5.66499 14C3.57488 14 1.63326 13.4203 0 12.4141C0.296966 12.4469 0.582471 12.4578 0.890863 12.4578C2.61546 12.4578 4.20305 11.9 5.47083 10.9485C3.84899 10.9156 2.48985 9.89845 2.02156 8.49844C2.25001 8.53123 2.47842 8.55311 2.71829 8.55311C3.0495 8.55311 3.38074 8.50934 3.6891 8.43282C1.99875 8.10467 0.730936 6.68281 0.730936 4.96562V4.92189C1.22204 5.18439 1.79315 5.34845 2.39844 5.3703C1.40478 4.73591 0.753788 3.65311 0.753788 2.4281C0.753788 1.77186 0.936496 1.1703 1.25631 0.645296C3.07232 2.78904 5.80203 4.18902 8.86293 4.34217C8.80583 4.07967 8.77155 3.80626 8.77155 3.53282C8.77155 1.58592 10.4162 0 12.4607 0C13.5228 0 14.4822 0.426561 15.1561 1.11562C15.9898 0.962507 16.7893 0.66718 17.4975 0.262502C17.2233 1.08283 16.6409 1.7719 15.8756 2.20937C16.618 2.13284 17.3376 1.93592 18 1.66251C17.4975 2.36248 16.8693 2.9859 16.1498 3.48905Z" fill="#696969"/>
                  </svg>
                </a>

                <a v-if="user.instagram" :href="user.instagram">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.00179 3.8978C5.73162 3.8978 3.9005 5.72933 3.9005 8C3.9005 10.2707 5.73162 12.1022 8.00179 12.1022C10.2719 12.1022 12.1031 10.2707 12.1031 8C12.1031 5.72933 10.2719 3.8978 8.00179 3.8978ZM8.00179 10.667C6.53475 10.667 5.33542 9.47094 5.33542 8C5.33542 6.52906 6.53118 5.33304 8.00179 5.33304C9.47239 5.33304 10.6682 6.52906 10.6682 8C10.6682 9.47094 9.46882 10.667 8.00179 10.667ZM13.2274 3.73C13.2274 4.26197 12.7991 4.68682 12.2708 4.68682C11.739 4.68682 11.3142 4.2584 11.3142 3.73C11.3142 3.20161 11.7426 2.77318 12.2708 2.77318C12.7991 2.77318 13.2274 3.20161 13.2274 3.73ZM15.9438 4.7011C15.8831 3.41939 15.5904 2.28406 14.6516 1.34866C13.7165 0.413254 12.5814 0.120495 11.2999 0.0562312C9.97925 -0.0187437 6.02075 -0.0187437 4.70006 0.0562312C3.4222 0.116925 2.28712 0.409684 1.34835 1.34509C0.409593 2.28049 0.120468 3.41582 0.0562186 4.69753C-0.0187395 6.01852 -0.0187395 9.97791 0.0562186 11.2989C0.116899 12.5806 0.409593 13.7159 1.34835 14.6513C2.28712 15.5867 3.41863 15.8795 4.70006 15.9438C6.02075 16.0187 9.97925 16.0187 11.2999 15.9438C12.5814 15.8831 13.7165 15.5903 14.6516 14.6513C15.5868 13.7159 15.8795 12.5806 15.9438 11.2989C16.0187 9.97791 16.0187 6.02209 15.9438 4.7011ZM14.2376 12.7163C13.9592 13.416 13.4202 13.9551 12.717 14.2372C11.664 14.6549 9.16542 14.5585 8.00179 14.5585C6.83815 14.5585 4.33597 14.6513 3.28656 14.2372C2.58695 13.9587 2.04796 13.4196 1.76598 12.7163C1.34835 11.6631 1.44473 9.1639 1.44473 8C1.44473 6.8361 1.35192 4.33337 1.76598 3.28372C2.04439 2.58396 2.58338 2.04485 3.28656 1.7628C4.33954 1.34509 6.83815 1.44148 8.00179 1.44148C9.16542 1.44148 11.6676 1.34866 12.717 1.7628C13.4166 2.04128 13.9556 2.58039 14.2376 3.28372C14.6552 4.33694 14.5588 6.8361 14.5588 8C14.5588 9.1639 14.6552 11.6666 14.2376 12.7163Z" fill="#696969"/>
                  </svg>
                </a>

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