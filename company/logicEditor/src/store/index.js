import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import axios from 'axios'
import { getdata, removeData } from '../api/api-pro';
export default new Vuex.Store({
  state: {
    data: [],
    pokeData:null,
    partsData:null,
    partsActiveID:null,
    formShow:null,
  },
  mutations: {
    setData(state, data) {
      state.data = data;
    },
    setPokeData(state, data) {
      state.pokeData = data;
    },
    setPartsData(state, data) {
      state.partsData = data;
    },
    partsActive(state, data) {
      state.partsActiveID = data;
    }, 
    formShow(state, data) {
      state.formShow = data;
    },
  },
  actions: {  
    updata({ state, commit }) {  
      return new Promise((resolve, reject) => { 
        let levelGroup = state.data.filter(e => e.id == state.pokeData[1].id)[0]
        let klondike = levelGroup.levelPack.filter(e => e.id == state.pokeData[2].id)[0]
        let poke = klondike.levels.filter(e => e.id == state.pokeData[3].id)[0]
        let parts = poke.parts.filter(e => e.id == state.partsData[4].id)[0]
        commit('setPartsData', [ 'pokeParts', levelGroup, klondike, poke, parts]);
        commit('formShow', 'pokeParts')
      });  
    },
    updataPoke({ state, commit }) {  
      return new Promise((resolve, reject) => { 
        let levelGroup = state.data.filter(e => e.id == state.pokeData[1].id)[0]
        let klondike = levelGroup.levelPack.filter(e => e.id == state.pokeData[2].id)[0]
        let poke = klondike.levels.filter(e => e.id == state.pokeData[3].id)[0]
        commit('setPokeData', [ 'poke', levelGroup, klondike, poke]);
        commit('formShow', 'poke')
      });  
    },

    fetchData({ commit }) {  
      return new Promise((resolve, reject) => {  
        getdata()  
          .then(res => {
            commit('setData', res.data);
            resolve(res.data);
          })  
          .catch(err => {  
            console.error(err);  
            reject(err); // 拒绝 Promise 并返回错误  
          });  
      });  
    },
    
    pokeData({ commit }, data) { 
      return new Promise((resolve, reject) => {
        commit('formShow', 'poke')
        commit('setPokeData', data);
      });  
    },

    partsData({ commit }, data) { 
      return new Promise((resolve, reject) => {
        commit('formShow', 'pokeParts')
        commit('setPartsData', data);
      });  
    },
    
    partsActive({ state, commit }, data) { 
      return new Promise((resolve, reject) => {
        let levelGroup = state.data.filter(e => e.id == state.pokeData[1].id)[0]
        let klondike = levelGroup.levelPack.filter(e => e.id == state.pokeData[2].id)[0]
        let poke = klondike.levels.filter(e => e.id == state.pokeData[3].id)[0]
        let parts = poke.parts.filter(e => e.id == data)[0]
        commit('setPartsData', [ 'pokeParts', levelGroup, klondike, poke, parts]);
        commit('formShow', 'pokeParts')
        commit('partsActive', data);
      });  
    },
  },
  // 严格模式
  strict: true
}) 

