<template>
  <section id="family-catechism" class="fc">

      <router-view />

      <header class="fc--header">

          <div class="fc--header--masthead"></div>

          <h1><?php _e( 'Family Catechism', familycatechism()->get_id() ); ?></h1>

      </header>

      <article class="fc--answer">

          <header class="fc--answer--header">

              <nav class="columns is-vcentered">
                  <div class="fc--answer--header--question-number column is-narrow">
                      <span class=""><?php _e( 'Q', familycatechism()->get_id() ); ?>{{ qNumber }}</span>
                  </div>

                  <div class="column fc--answer--header--arrows is-narrow">
                      <span class="fc--answer--header--arrows-previous">^</span>
                      <span class="fc--answer--header--arrows-next"><</span>
                  </div>

                  <div class="column fc--answer--header--questions">
                      <ul>
                          <li>Question 1</li>
                      </ul>
                  </div>

                  <div class="column is-narrow">
                      <div class="fc--answer--header--actions">search</div>
                  </div>

              </nav>

              <p class="fc--answer--header--breadcrumb">{{ location.section }}, {{ location.part }} > {{ location.chapter }}</p>
          </header>

          <h2 class="fc--answer--header"><?php _e( 'Answer', familycatechism()->get_id() ); ?></h2>

          <div class="fc--answer--content" v-html="answer"></div>

          <footer>
              <a class="fc--answer--last-question" :href="nav.previous" v-if="nav.previous"><?php _e( 'Last Question', familycatechism()->get_id() ); ?></a>
              <a class="fc--answer--next-question" :href="nav.next" v-if="nav.next"><?php _e( 'Next Question', familycatechism()->get_id() ); ?></a>
          </footer>

          <section class="fc--answer--meta">

              <nav class="fc--answer--meta--navigation"></nav>

              <article class="fc--answer--meta--video">
                  <h1></h1>
              </article>
          </section>

      </article>

  </section>
</template>

<script>
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

      nav: {
        previous: '#',
        next: ''
      }
    }
  }
}
</script>

<style lang="scss">
    @import "assets/scss/app";
</style>
