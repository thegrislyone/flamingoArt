<template>
  <div class="search">
    <input 
      type="text" 
      name="main-searh" 
      id="main-searh"
      autocomplete="off"
      placeholder="Поиск..."
      :class="{
        'search_opened': searchOpened
      }"
      v-click-outside="hideResults"
      v-model="searchValue"
      v-debounce:400ms.lock="getSearchTip"
      @keydown.enter="getSearchResults"
    >

    <div
      v-show="tipsLoading"
      class="search__tips-loading"
    >
      
    </div>

    <button 
      v-if="searchOpened || windowWidth < 1366" class="search__close pointer"
      @click="searchClose"
    ></button>

    <transition name="fade">
      <div
        v-if="searchOpened"
        class="search__tips"
        :class="{
          'search__tips_wide': windowWidth < 1366
        }"
      >

        <template v-if="tipsErrors.length">
          {{ tipsErrors[0] }}
        </template>

        <template v-else>
          <div v-if="tipsTags.length" class="search__tip-tags">

            <div 
              v-for="tipTag in tipsTags"
              :key="tipTag.id"
              class="search__tip pointer"
              @click="clickTip(tipTag)"
            >
              
              <div class="search__tag">
                #{{ tipTag.name }}
              </div>

            </div>

          </div>

          <div v-if="tipsItems.length" class="search__tip-items">

            <div 
              v-for="tipItem in tipsItems"
              :key="tipItem.id"
              class="search__tip pointer"
              @click="clickTip(tipItem)"
            >
              
              <div class="search__item">
                {{ tipItem.name }}
              </div>

            </div>

          </div>
        </template>

        

      </div>
    </transition>
  </div>
</template>

<script>

import { debounce } from 'vue-debounce'

export default {
  props: {
    close: {
      type: Boolean,
      default: false
    },
    placeholder: String
  },
  data() {
    return {

      tipsLoading: false,
      searchTips: null,

      searchValue: '',

      searchResults: [],
      searchOpened: false,

      // searchQueryDebounced: null
    }
  },
  computed: {
    tipsErrors() {
      return (this.searchTips && this.searchTips.errors) ? this.searchTips.errors : []
    },
    tipsTags() {
      return (this.searchTips && this.searchTips.tags) ? this.searchTips.tags : []
    },
    tipsItems() {
      return (this.searchTips && this.searchTips.items) ? this.searchTips.items : []
    },
    windowWidth() {
      return this.$store.getters.windowWidth
    },
  },
  created() {
    // this.searchQueryDebounced = debounce(this.searchQuery, 400)
  },
  methods: {
    clickTip(item) {
      this.searchValue = item.name
      this.getSearchResults()
    },
    getSearchTip() {

      this.tipsLoading = true
      this.searchTips = null

      if (!this.searchValue) {
        return
      }

      this.$http.get('/api/tags/tags-popular-increase?search-query=' + this.searchValue)
      
      let url = new URL(window.location.origin + '/api/search/get-search-tips')
      url.searchParams.set('search-query', this.searchValue)

      this.$http.get(url)
        .then(response => {
          const data = response.data

          this.searchTips = data
          this.searchOpened = true

        })
        .then(() => {
          this.tipsLoading = false
        })

    },
    getSearchResults() {

      this.$router.push('/search?search-query=' + this.searchValue)

      this.searchClose()

    },
    hideResults() {
      this.searchOpened = false
    },
    searchClose() {

      this.searchValue = ''
      this.searchTips = null
      this.searchOpened = false

      if (this.windowWidth <= 1366) {
        this.$emit('mobile-close')
      } else {
        document.querySelector('#main-searh').blur()
      }
      
    }
  }
}
</script>
