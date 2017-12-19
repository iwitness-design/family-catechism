<template>
	<article v-if="isActive" :class="className">
		<h1 class="fc--answer--meta--module--title">{{ name }}</h1>
		<slot></slot>
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
	computed : {
      className () {
        return 'fc--answer--meta--module fc--answer--meta--' + this.icon;
	  }
	},
    props: {
      'name'    : {required: true},
      'icon'    : {required: true},
      'selected': {default: false}
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
	/*@import "../scss/components/Header";*/
</style>
