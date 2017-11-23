<template>
	<fc-accordion>

		<fc-accordion-item class="" v-for="(exercise, index) in exercises" :name="heading(exercise)" :key="index" :hasContent="hasContent(exercise)">
			<div v-if="hasContent(exercise)" v-html="exercise.answer"></div>
		</fc-accordion-item>

	</fc-accordion>
</template>

<script>
  import VueYouTubeEmbed from 'vue-youtube-embed'
  import fcAccordion from '../fcAccordion.vue';
  import fcAccordionItem from '../fcAccordionItem.vue';

  export default {
    props     : ['exercises'],
    methods   : {
      heading (exercise) {
        if (exercise.question.length) {
          return exercise.question;
        }

        return exercise.action;
      },
	  content (exercise) {
        if ( exercise.answer.length ) {
          return exercise.answer;
		}

		return '';
	  },
	  hasContent (exercise) {
        let content = this.content(exercise);
        return !!(content.length);
	  }
    },
    components: {VueYouTubeEmbed, fcAccordion, fcAccordionItem}
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
</style>
