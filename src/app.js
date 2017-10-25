// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App.vue'
import router from './router'

Vue.config.productionTip = false

window.currentQuestion = {
  title: 'This is the first question'
}

/* eslint-disable no-new */
window.fcApp = new Vue({
  el: '#family-catechism',
  router,
  template: '<App/>',
  components: { App }
})
