<template>
	<section class="fc--answer--meta">

		<nav class="fc--answer--meta--navigation">
			<ul class="columns is-mobile">
				<li v-for="module in modules" :class="'is-one-third-mobile column ' + module.icon">
					<a href="#" @click="selectModule(module, $event)" :class="module.isActive ? 'current' : ''">{{ module.name }}<span><img :src="getIcon( module.icon )" /></span></a>
				</li>
			</ul>
		</nav>

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
      this.modules = this.$children;
    },
    methods: {
      selectModule (selectedModule, e) {
        e.preventDefault();
        this.modules.forEach(module => {
          module.isActive = (module.name === selectedModule.name);
        });
      },
	  getIcon ( icon ) {
        return '/wp-content/plugins/family-catechism/dist/assets/svg/' + icon + '.svg';
	  },
	  getClass ( module ) {
        return 'is-one-third-mobile column ' + ( module.isActive ? 'current' : '' );
	  }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
	/*@import "../scss/components/Header";*/
</style>
