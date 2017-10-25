<template>
	<article class="fc--answer--meta--prayer">
		<h1>{{ lang[$route.name] }}</h1>
	</article>
</template>

<script>
  export default {
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
