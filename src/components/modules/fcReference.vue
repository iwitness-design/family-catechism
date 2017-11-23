<template>
	<fc-accordion>

		<fc-accordion-item class="" v-for="(reference, index) in references" :name="reference.referencefull" :key="reference.referencefull" :hasContent="reference.text.length > 0" :selected="0 === index">
			<div v-if="reference.text.length > 0" v-html="reference.text"></div>
		</fc-accordion-item>

	</fc-accordion>
</template>

<script>
  import VueYouTubeEmbed from 'vue-youtube-embed'
  import fcAccordion from '../fcAccordion.vue';
  import fcAccordionItem from '../fcAccordionItem.vue';

  export default {
    props     : ['references'],
    components: {VueYouTubeEmbed, fcAccordion, fcAccordionItem},
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
</style>
