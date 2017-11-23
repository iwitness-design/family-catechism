<template>
	<section class="fc--accordion" v-on:toggleItem="this.selectItem">
		<slot></slot>
	</section>
</template>

<script>
  export default {
    data () {
      return {
        modules : [],
        lang    : fcLang,
      }
    },
    created () {
      this.items = this.$children;
    },
    methods: {
      selectItem (selectedItem, e) {
        e.preventDefault();
        debugger;
        this.items.forEach(item => {
          item.isActive = (item.name === selectedItem.name);
        });
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
	/*@import "../scss/components/Header";*/
</style>
