<template>
  <section id="family-catechism" class="fc">

      <header class="fc--header">

          <div class="fc--header--masthead"></div>

          <h1>{{ lang.name }}</h1>

      </header>

      <article class="fc--answer">

          <fcHeader/>

          <h2 class="fc--answer--header">{{ lang.answer }}</h2>

          <div class="fc--answer--content" v-html="answer"></div>

          <footer>
              <a class="fc--answer--last-question" :href="nav.previous" v-if="nav.previous">{{ lang.qlast }}</a>
              <a class="fc--answer--next-question" :href="nav.next" v-if="nav.next">{{ lang.qnext }}</a>
          </footer>

          <section class="fc--answer--meta">

              <nav class="fc--answer--meta--navigation">
                  <ul class="columns is-mobile">
                      <li v-for="item in navItems" :class="item.path + ' is-one-third-touch column'">
                          <router-link :to="{ name: item.path, params: { id: qNumber } }" replace>{{ lang[item.path] }}<i :class="'icon-play-circle ' + item.path"></i></router-link>
                      </li>
                  </ul>
              </nav>

              <router-view></router-view>

          </section>

      </article>

  </section>
</template>

<script>
import fcHeader from './components/fcHeader.vue'

export default {
  name: 'app',
  data () {
    return {
      qNumber: 36,
      answer: 'Here is a sample answer',

      location: {
        section: 'Section 1',
        part: 'Part 3: Creation: Material and Spiritual',
        chapter: 'Chapter 8: Creation'
      },

      navItems: [
        {path: 'video'},
        {path: 'prayer'},
        {path: 'scripture'},
        {path: 'catechism'},
        {path: 'church-docs'},
        {path: 'papal-docs'},
        {path: 'other-docs'},
        {path: 'doctrine'},
        {path: 'thought-provokers'}
      ],

      nav: {
        previous: '#',
        next: ''
      },

      /** global fcLang */
      lang: fcLang,
    }
  },
  components: {
    fcHeader
  }
}
</script>

<style lang="scss">
    @import "scss/app";
</style>
