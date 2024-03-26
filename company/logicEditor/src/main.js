import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
Vue.use(ElementUI)

import VueVirtualScroller from 'vue-virtual-scroller'
import "vue-virtual-scroller/dist/vue-virtual-scroller.css"
Vue.use(VueVirtualScroller)

import GlobalDialog from './components/GlobalDialog.vue';
Vue.component('GlobalDialog', GlobalDialog);

import previewFbx from './views/main/previewFbx.vue';
Vue.component('previewFbx', previewFbx);

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
