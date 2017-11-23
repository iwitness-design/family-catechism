<template>
	<section id="family-catechism" class="fc">

		<header class="fc--header">

			<div class="fc--header--local">
				<ul>
					<li><a :href="lang.url + '?l=english'">English</a></li>
					<li><a :href="lang.url + '?l=spanish'">Español</a></li>
					<li><a :href="lang.url + '?l=filipino'">Filipino</a></li>
					<li><a :href="lang.url + '?l=chinese'">中文</a></li>
				</ul>
			</div>

			<div class="fc--header--masthead"><img src="/wp-content/themes/afc/assets/img/logos/regular-full.png" /></div>

			<h1>{{ lang.name }}</h1>

		</header>

		<article class="fc--answer">

			<div class="fc--answer--loading" v-if="loading"></div>

			<fc-header :question="question" :questions="questions" :nav="nav"></fc-header>

			<h2 class="fc--answer--header">{{ lang.answer }}</h2>

			<div class="fc--answer--content" v-html="question.content.rendered"></div>

			<lightbox :images="images" :caption="true"></lightbox>

			<footer>
				<router-link class="fc--answer--last-question" :to="{ path: '/question-' + nav.previous }" v-if="nav.previous" replace>{{ lang.qlast }}</router-link>
				<router-link class="fc--answer--next-question" :to="{ path: '/question-' + nav.next }" v-if="nav.next" replace>{{ lang.qnext }}</router-link>
			</footer>

			<fc-modules>

				<fc-module :name="lang.video" icon="video" :selected="true">
					<fc-video :videos="question.video_answer"></fc-video>
				</fc-module>

				<fcModule :name="lang.prayer" icon="prayer">
					<prayer :chapter="question.chapter" :prayer="question.prayer"></prayer>
				</fcModule>

				<fcModule :name="lang.scripture" icon="scripture">
					<reference :references="crossReferences('scripture')"></reference>
				</fcModule>

				<fcModule :name="lang.catechism" icon="catechism">
					<reference :references="crossReferences('catechism')"></reference>
				</fcModule>

				<fcModule :name="lang['church-docs']" icon="church-docs">
					<reference :references="crossReferences('church documents')"></reference>
				</fcModule>

				<fcModule :name="lang['papal-docs']" icon="papal-docs">
					<reference :references="crossReferences('papal')"></reference>
				</fcModule>

				<fcModule :name="lang['other-docs']" icon="other-docs">
					<other-docs :question="question"></other-docs>
				</fcModule>

				<fcModule :name="lang.doctrine" icon="doctrine">
					<doctrine :exercises="question.exercises"></doctrine>
				</fcModule>

				<fcModule :name="lang['thought-provokers']" icon="thought-provoker">
					<thought-provoker :provokers="question.thought_provokers"></thought-provoker>
				</fcModule>

			</fc-modules>

		</article>

	</section>
</template>

<script>
  import fcHeader from './components/fcHeader.vue';
  import fcModules from './components/fcModules.vue';
  import fcModule from './components/fcModule.vue';
  import catechism from './components/modules/fcCatechism.vue';
  import doctrine from './components/modules/fcDoctrine.vue';
  import otherDocs from './components/modules/fcOtherDocs.vue';
  import prayer from './components/modules/fcPrayer.vue';
  import reference from './components/modules/fcReference.vue';
  import thoughtProvoker from './components/modules/fcThoughtProvoker.vue';
  import fcVideo from './components/modules/fcVideo.vue';
  import fcLightbox from 'vlightbox';
  import vueCookie from 'vue-cookies';

  export default {
    name      : 'app',
    data () {
      return {
        loading    : false,
        questions  : [],
        question   : {
          number          : 0,
          title           : '',
          content         : {
            rendered: ''
          },
          chapter         : {
            prayer: ''
          },
          prayer          : '',
          cross_references: [],
          exercises       : []
        },
        isSearching: true,
        nav        : {
          previous: '',
          next    : ''
        },

        /** global fcLang */
        lang: fcLang,
      }
    },
    watch     : {
      '$route' (to, from) {
        this.getCurrentQuestion();
      }
    },
    created() {
      this.getCurrentQuestion();
      this.getAllQuestions();
    },
    computed  : {
      images () {
        let images = [];

        for (let image in this.question.images) {
          images.push({
            src    : '/wp-content/plugins/family-catechism/dist/assets/images/' + this.lang.local + '/' + this.question.images[image].filename,
            caption: (
              this.question.images[image].caption.length
            ) ? this.question.images[image].caption : ''
          });
        }

        return images;
      }
    },
    methods   : {
      getCurrentQuestion () {
        this.loading = true;
        jQuery.get('/wp-json/wp/v2/questions/?fc_language=' + this.lang.localID + '&numbers=' + this.$route.params.id, (data) => {
          this.question = window.currentQuestion = data[0];
          this.getNav();
          this.loading = false;
        });
      },
      getAllQuestions () {
        this.error = this.post = null
        this.loading = true
        // replace `getPost` with your data fetching util / API wrapper
        jQuery.get('/wp-json/wp/v2/questions/all/?fc_language=' + this.lang.localID, (data) => {
          this.questions = data;
          this.getNav();
        });
      },
      crossReferences(type) {
        return this.question.cross_references.filter(
          function (ref) { return ref.type.toLowerCase().indexOf(type.toLowerCase()) >= 0 });
      },

      getNav () {
        let q, index;

        this.nav = {previous: '', next: ''};

        for (q in this.questions) {
          if (this.questions[q].number == this.question.number) {
            index = Number(q);
            if (index > 0) {
              this.nav.previous = this.questions[index - 1].number;
            }

            if (undefined !== this.questions[index + 1]) {
              this.nav.next = this.questions[index + 1].number;
            }

            return;
          }
        }
      }
    },
    components: {
      fcHeader,
      fcModules,
      fcModule,
      doctrine,
      otherDocs,
      prayer,
      reference,
      thoughtProvoker,
      fcVideo,
      fcLightbox
    },
  }
</script>

<style lang="scss">
	@import "scss/app";
</style>
