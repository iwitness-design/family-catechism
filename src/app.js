// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import VueYouTubeEmbed from 'vue-youtube-embed'
import fcLightbox from 'vlightbox'
import vueCookies from 'vue-cookies'

Vue.config.productionTip = false;
Vue.use(VueYouTubeEmbed);
Vue.use(fcLightbox);
Vue.use(vueCookies);

window.fcPath = '/wp-content/plugins/family-catechism/';
window.allQuestions = [];
window.currentQuestion = {
  title  : '',
  content: {
    rendered: ''
  },
};

/* eslint-disable no-new */
window.fcApp = new Vue({
  el: '#family-catechism',
  router,
  template: '<App/>',
  components: { App }
})
