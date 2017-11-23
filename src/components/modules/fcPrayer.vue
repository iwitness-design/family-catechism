<template>

	<fc-accordion>

		<fc-accordion-item class="" :name="lang['summary-prayer']" v-if="prayer" :selected="true">
			<div v-html="prayer"></div>
		</fc-accordion-item>

		<fc-accordion-item class="" :name="lang['chapter-prayer']" :selected="! prayer.length > 0">
			<div v-html="chapter.prayer"></div>
		</fc-accordion-item>

	</fc-accordion>

</template>

<script>
  import fcAccordion from '../fcAccordion.vue';
  import fcAccordionItem from '../fcAccordionItem.vue';

  export default {
    data () {
      return {
        lang: fcLang,
      }
    },
	props: {
      chapter: {
        default: { prayer: '' }
	  },
	  prayer : ''
	},
    components: {fcAccordion, fcAccordionItem}
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
</style>
