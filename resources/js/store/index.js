import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {

    user: window.USER || {},

    windowParameters: {
      windowHeight: window.innerHeight,
      windowWidth: window.innerWidth
    }
  },
  getters: {
    isAuthorizate: state => Object.keys(state.user).length,
    user: state => state.user,

    favorites: state => {
      if (Object.keys(state.user).length) {
        return state.user.favorites
      }
      return []
    },

    windowParameters: state => state.windowParameters,
    windowWidth: state => state.windowParameters.windowWidth,
    windowHeight: state => state.windowParameters.windowHeight,
  },
  actions: {
    windowResize({commit}) {
      commit('windowParametersChange', {
        width: window.innerWidth,
        height: window.innerHeight
      })
    }
  },
  mutations: {
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