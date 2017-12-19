<template>
	<header class="fc--answer--header">

		<nav class="columns is-vcentered is-mobile">
			<div class="fc--answer--header--question-number column is-narrow bg-red">
				<span class="">{{ lang.q }}{{ question.number }}</span>
			</div>

			<div class="column is-narrow fc--answer--header--arrows">
				<router-link class="fc--answer--header--arrows-previous icon-chevron-up" :to="{ path: '/question-' + nav.previous }" @click.native="resetSearch" replace>
					<span class="is-hidden">{{ lang.qlast }}</span></router-link>
				<router-link class="fc--answer--header--arrows-next icon-chevron-down" :to="{ path: '/question-' + nav.next }" @click.native="resetSearch" replace>
					<span class="is-hidden">{{ lang.qnext }}</span></router-link>
			</div>

			<div class="column bg-gray fc--answer--header--questions">
				<div>
					<h1 @click="isNavigating = !isNavigating" v-if="!isSearching">{{ question.title.rendered }}</h1>
					<input type="text" ref="search" v-model="search" v-show="isSearching" :autofocus="isSearching" />
				</div>
			</div>

			<div class="column is-narrow fc--answer--header--actions">
				<a href="#" @click.prevent="navClick" :class="'icon-navicon ' + (isNavigating ? 'active' : '' )"><span class="is-hidden">{{ lang.search }}</span></a>
				<a href="#" @click.prevent="searchClick" :class="'icon-search ' + (isSearching ? 'active' : '' )"><span class="is-hidden">{{ lang.search }}</span></a>
			</div>

		</nav>

		<div v-if="isNavigating" class="fc--answer--header--questions--search">
			<ul>
				<li v-for="(sections, section) in getSortedQuestions" :class="'section-cont ' + getSectionColor(section)">
					<h3 class="section" v-text="getSectionName(section)"></h3>
					<ul>
						<li v-for="(parts, part) in sections" :class="'part-cont ' + getSectionColor(part)">
							<h4 class="part" v-text="getSectionName(part)"></h4>

							<ul>
								<li v-for="(chapters, chapter) in parts" :class="'chapter-cont ' + getSectionColor(chapter)">
									<h5 class="chapter" v-text="getSectionName(chapter)"></h5>
									<ul>
										<li v-for="question in chapters">
											<router-link :to="{ path: '/question-' + question.number }" @click.native="resetSearch" data-color="#f00" replace>
												<span class="question-number">{{ lang.q }} {{ question.number }}:</span> {{ question.title }}
											</router-link>
										</li>
									</ul>
								</li>

							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</div>

		<p class="fc--answer--header--breadcrumb" v-if="question.section && question.part && question.chapter" v-html="getBreadcrumbs"></p>
	</header>
</template>

<script>
  import router from '../router'
  export default {
    props   : ['question', 'questions', 'nav'],
    data () {
      return {
        qNumber     : 1,
        lang        : fcLang,
        isSearching : false,
        isNavigating: false,
        search      : '',
        sections    : {},
      }
    },
    methods : {
      resetSearch () {
        this.isSearching = this.isNavigating = false;
      },
      navClick() {
        this.isSearching = false;
        this.isNavigating = !this.isNavigating;
      },
      searchClick () {
        this.isNavigating = this.isSearching = !this.isSearching;

        if (this.isSearching) {
          setTimeout(() => { this.$refs.search.focus() }, 10);
        }
      },
      getSectionName(sectionID) {
        if (undefined !== this.sections[sectionID]) {
          return this.sections[sectionID].name + ': ' + this.sections[sectionID].description;
        }
      },
      getSectionColor(sectionID) {
        if (undefined !== this.sections[sectionID]) {
          return this.sections[sectionID].color;
        }

        return 'red';
      }
    },
    computed: {
      getBreadcrumbs () {
        return this.question.section.name + ', ' + this.question.part.name + ': ' + this.question.part.description + ' > ' + this.question.chapter.name + ': ' + this.question.chapter.description;
      },
      getSortedQuestions () {
        let question = false,
          chapter = false,
          part = false,
          section = false,
          allQuestions = [],
          sections = {},
          parts = {},
          chapters = {}

        if (!Object.keys(this.questions).length) {
          return {};
        }

        allQuestions = this.questions.filter(q => (
                                                    q.section.name + ' ' + q.section.description + ' ' + q.part.name + ' ' + q.part.description + ' ' + q.chapter.name + ' ' + q.chapter.description + ' question ' + q.number + ': ' + q.title
                                                  ).toLowerCase().indexOf(this.search.toLowerCase()) >= 0);

        for (question in allQuestions) {

          this.sections[allQuestions[question]['chapter'].id] = allQuestions[question]['chapter'];
          this.sections[allQuestions[question]['part'].id] = allQuestions[question]['part'];
          this.sections[allQuestions[question]['section'].id] = allQuestions[question]['section'];

          if (part !== allQuestions[question]['part'].id) {
            if (part) {
              parts[part] = chapters;
              chapters = {};
            }

            if (section !== allQuestions[question]['section'].id) {
              if (section) {
                sections[section] = parts;
                parts = {};
              }

              section = allQuestions[question]['section'].id;
              sections[section] = {};
            }

            part = allQuestions[question]['part'].id;
            parts[part] = {};
          }

          if (chapter !== allQuestions[question]['chapter'].id) {
            chapter = allQuestions[question]['chapter'].id;
            chapters[chapter] = [];
          }

          chapters[chapter].push(allQuestions[question]);

        }

        if (undefined !== question && question) {
          parts[allQuestions[question]['part'].id] = chapters;
          sections[allQuestions[question]['section'].id] = parts;
        }

        return sections;
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
	@import "../scss/components/Header";
</style>
