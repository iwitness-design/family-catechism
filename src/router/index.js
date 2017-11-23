import Vue from 'vue'
import Router from 'vue-router'
import Video from '../components/modules/fcVideo.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path     : '/question-:id',
      alias    : '/question-:id/video',
      name     : 'video',
      component: Video
    },
    {
      path    : '*',
      redirect: {name: 'video', params: { id: 1 }}
    }
  ]
})
