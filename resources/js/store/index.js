import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    windowParameters: {
      windowHeight: window.innerHeight,
      windowWidth: window.innerWidth
    }
  },
  getters: {
    windowParameters: state => state.windowParameters,
    windowWidth: state => state.windowParameters.windowWidth,
    windowHeight: state => state.windowParameters.windowHeight
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
    }
  }
})