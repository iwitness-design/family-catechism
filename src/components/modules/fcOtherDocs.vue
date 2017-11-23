<template>
	<div v-html="question.cross_reference"></div>
</template>

<script>
  export default {
    props : ['question']
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
</style>
