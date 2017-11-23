<template>
	<fc-accordion>

		<fc-accordion-item class="" v-for="(provoker, index) in provokers" :name="provoker.question" :key="provoker.question">
			<div v-html="provoker.answer"></div>
		</fc-accordion-item>

	</fc-accordion>
</template>

<script>
  import VueYouTubeEmbed from 'vue-youtube-embed'
  import fcAccordion from '../fcAccordion.vue';
  import fcAccordionItem from '../fcAccordionItem.vue';
  export default {
    props : ['provokers'],
    components: {VueYouTubeEmbed, fcAccordion, fcAccordionItem},
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
</style>
