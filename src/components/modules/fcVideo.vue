<template>
	<div>

		<fc-accordion>

			<fc-accordion-item class="" v-for="(video, index) in videos" :name="video.answeredby" :key="video.youtubeid">
				<youtube :video-id="video.youtubeid"></youtube>
			</fc-accordion-item>

		</fc-accordion>

	</div>
</template>

<script>
  import VueYouTubeEmbed from 'vue-youtube-embed'
  import fcAccordion from '../fcAccordion.vue';
  import fcAccordionItem from '../fcAccordionItem.vue';

  export default {
    data () {
      return {
        lang: fcLang,
		validVideos : []
      }
    },
    props     : {
      videos: {
        default: () => { return [] }
      }
    },
    components: {VueYouTubeEmbed, fcAccordion, fcAccordionItem},
	methods : {
      getThumb (id) {
        return 'https://img.youtube.com/vi/' + id + '/hqdefault.jpg';
      },
      getValidVideos() {
        for (let video in this.videos) {
          let url = this.getThumb(this.videos[video].youtubeid);
          let img = new Image();
          img.src = url;
          img.onload = () => { this.validVideos.unshift(this.videos[video]) }
          img.onerror = () => { alert( video ) };
		}
      }
	}
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
</style>
