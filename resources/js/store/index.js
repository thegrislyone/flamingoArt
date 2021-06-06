import Vue from 'vue'
import Vuex from 'vuex'

import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {

    user: window.USER || {},

    windowParameters: {
      windowHeight: window.innerHeight,
      windowWidth: window.innerWidth
    },

    chatsList: []

  },
  getters: {

    isAuthorizate: state => state.user && Object.keys(state.user).length,
    user: state => state.user,

    favorites: state => {
      if (Object.keys(state.user).length) {
        return state.user.favorites
      }
      return null
    },

    unreadedMessages: state => {

      let amount = 0

      state.chatsList.forEach(chat => {
        amount += chat.unreaded_messages
      })

      return amount

    },

    windowParameters: state => state.windowParameters,
    windowWidth: state => state.windowParameters.windowWidth,
    windowHeight: state => state.windowParameters.windowHeight,
  },
  actions: {
    async getChatsList({commit}) {

      console.log('метод вызывается')

      const url_string = window.location.origin + '/api/chat/get-user-chats'
      let url = new URL(url_string)

      await axios.get(url.href)
        .then(response => {

          const data = response.data

          console.log(data)

          commit('setChatsList', data)

        })

    },
    windowResize({commit}) {
      commit('windowParametersChange', {
        width: window.innerWidth,
        height: window.innerHeight
      })
    }
  },
  mutations: {
    setChatsList(state, data) {
      state.chatsList = data
    },
    setFavorites(state, data) {
      state.user.favorites = data
    },
    setUser(state, value) {
      state.user = value
    },
    windowParametersChange(state, {width, height}) {
      state.windowParameters.windowHeight = height
      state.windowParameters.windowWidth = width
    },
    setSignFormOpenedStatus(state, status) {
      state.signForm.formOpened = status
    },
    setSignFormMode(state, key) {
      for (const index in state.signForm.formMode) {
        state.signForm.formMode[index] = false
      }
      state.signForm.formMode[key] = true
    }
  }
})