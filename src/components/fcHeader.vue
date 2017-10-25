<template>
	<header class="fc--answer--header">

		<nav class="columns is-vcentered">
			<div class="fc--answer--header--question-number column is-narrow">
				<span class="">{{ lang.q }}{{ qNumber }}</span>
			</div>

			<div class="column fc--answer--header--arrows is-narrow">
				<span class="fc--answer--header--arrows-previous fa fa-angle-up"><span class="is-hidden">{{ lang.qlast }}</span></span>
				<span class="fc--answer--header--arrows-next fa fa-angle-down"><span class="is-hidden">{{ lang.qnext }}</span></span>
			</div>

			<div class="column fc--answer--header--questions">
				<h1>{{ question.title }}</h1>
				<ul v-if="isSearching">
					<li>Question 1</li>
				</ul>
			</div>

			<div class="column is-narrow">
				<div class="fc--answer--header--actions">
					<i class="fa fa-search"></i><span class="is-hidden">{{ lang.search }}</span></div>
			</div>

		</nav>

		<p class="fc--answer--header--breadcrumb">{{ location.section }}, {{ location.part }} > {{ location.chapter }}</p>
	</header>
</template>

<script>
  export default {
    data () {
      return {
        msg        : 'Here is the view message 1234',
        qNumber    : 1,
        lang       : fcLang,
        question   : currentQuestion,
        isSearching: false,
        location   : {
          section: 'test',
          part   : 'testing',
          chapter: 'test123'
        }

      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
	@import "../scss/components/Header";
</style>
