import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    authorization: false,
    authFormOpened: false,

    themeModes: {
      dark: true,
      bright: false
    },

    windowParameters: {
      windowHeight: window.innerHeight,
      windowWidth: window.innerWidth
    }
  },
  getters: {
    isAuthorizate: state => state.authorization,
    isFormOpened: state => state.authFormOpened,

    themeMode: state => {
      for (const [key, value] of Object.entries(state.themeModes)) {
        if (value) return key
      }
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
    windowParametersChange(state, {width, height}) {
      state.windowParameters.windowHeight = height
      state.windowParameters.windowWidth = width
    },
    setFormOpenedStatus(state, status) {
      state.authFormOpened = status
    }
  }
})