import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: window.USER || {},

    signForm: {
      formOpened: false,
      formMode: {
        authorization: true,
        registation: false
      }
    },

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
    user: state => state.user,

    isSignFormOpened: state => state.signForm.formOpened,
    signFormMode: (state) => {
      for (const [key, value] of Object.entries(state.signForm.formMode)) {
        if (value) return key
      }
    },

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