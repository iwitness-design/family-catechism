<template>
	<article class="fc--answer--meta--prayer">
		<h1>{{ lang.catechism }}</h1>

		<div v-for="scripture in scriptures">
			<h4>{{ scripture.referencefull }}</h4>
			<p v-if="scripture.text.length > 0">{{ scripture.text }}</p>
		</div>
	</article>
</template>

<script>
  export default {
    props : ['catechisms'],
    data () {
      return {
        lang       : fcLang,
        question   : currentQuestion,
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
</style>
