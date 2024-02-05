import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import axios from 'axios'
import { GetData, removeData } from '../api/api-pro';
export default new Vuex.Store({
  state: {
    data: []
  },
  mutations: {
    setData(state, data) {
      state.data = data;
    }
  },
  actions: {
    fetchData({ commit }) {
      return new Promise((resolve, reject) => {
        GetData({name:'levelDifficulty'})
          .then(res => {
            res == null ? commit('setData', []):commit('setData', res);
            resolve(); 
          })
          .catch(error => {
            console.error('接口请求失败:', JSON.parse(sessionStorage.getItem('name')), error);
            reject(error);
          });
      });
    }
  },
  // 严格模式
  strict: true
})
