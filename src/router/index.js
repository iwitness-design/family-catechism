import Vue from 'vue'
import Router from 'vue-router'
import Video from '../components/modules/fcVideo.vue'
import Prayer from '../components/modules/fcPrayer.vue'
import Scripture from '../components/modules/fcScripture.vue'
import Catechism from '../components/modules/fcCatechism.vue'
import ChurchDocs from '../components/modules/fcChurchDocs.vue'
import PapalDocs from '../components/modules/fcPapalDocs.vue'
import OtherDocs from '../components/modules/fcOtherDocs.vue'
import Doctrine from '../components/modules/fcDoctrine.vue'
import ThoughtProvokers from '../components/modules/fcThoughtProvoker.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path     : '/',
      name     : 'video',
      component: Video
    },
    {
      alias    : '/:id',
      path     : '/:id/video',
      name     : 'video',
      component: Video
    },
    {
      path     : '/:id/prayer',
      name     : 'prayer',
      component: Prayer
    },
    {
      path     : '/:id/scripture',
      name     : 'scripture',
      component: Scripture
    },
    {
      path     : '/:id/catechism',
      name     : 'catechism',
      component: Catechism
    },
    {
      path     : '/:id/church-docs',
      name     : 'church-docs',
      component: ChurchDocs
    },
    {
      path     : '/:id/papal-docs',
      name     : 'papal-docs',
      component: PapalDocs
    },
    {
      path     : '/:id/other-docs',
      name     : 'other-docs',
      component: OtherDocs
    },
    {
      path     : '/:id/doctrine',
      name     : 'doctrine',
      component: Doctrine
    },
    {
      path     : '/:id/thought-provokers',
      name     : 'thought-provokers',
      component: ThoughtProvokers
    },
    {
      path    : '*',
      redirect: {name: 'video'}
    }
  ]
})
