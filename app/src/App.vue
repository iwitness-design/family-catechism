<template>
  <section id="family-catechism" class="fc">

      <header class="fc--header">

          <div class="fc--header--masthead"></div>

          <h1><?php _e( 'Family Catechism', familycatechism()->get_id() ); ?></h1>

      </header>

      <article class="fc--answer">

          <header class="fc--answer--header">

              <nav>
                  <span class="fc--answer--header--question-number"><?php _e( 'Q', familycatechism()->get_id() ); ?>{{ qNumber }}</span>

                  <div class="fc--answer--header--arrows">
                      <span class="fc--answer--header--arrows-previous"></span>
                      <span class="fc--answer--header--arrows-next"></span>
                  </div>

                  <ul class="fc--answer--header--questions"></ul>

                  <div class="fc--answer--header--actions"></div>

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

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
