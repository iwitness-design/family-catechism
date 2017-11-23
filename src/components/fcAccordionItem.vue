<template>
	<article :class="getClass">
		<h1 @click="toggleItem" class="fc--accordion--item--title">{{ name }}</h1>
		<div v-if="isActive && hasContent">
			<slot></slot>
		</div>
	</article>
</template>

<script>
  export default {
    data () {
      return {
        lang    : fcLang,
        isActive: false
      }
    },
    created () {
      this.isActive = this.selected;
    },
    computed: {
      getClass () {
        return 'fc--accordion--item ' + (
            this.isActive ? 'open ' : 'closed '
          ) + (
                 this.hasContent ? 'has-content' : ''
               );
      }
    },
    methods : {
      toggleItem (e) {
        if (this.hasContent) {
          e.preventDefault();
          this.isActive = !this.isActive;
        }
      }
    },
    props   : {
      'name'      : {required: true},
      'selected'  : {default: false},
      'hasContent': {default: true}
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
	/*@import "../scss/components/Header";*/
</style>
