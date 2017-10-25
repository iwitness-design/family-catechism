<template>
  <div class="hello">
    <h1>Hello!!!!</h1>
    <h1>{{ msg }}</h1>
  </div>
</template>

<script>
export default {
  name: 'Test',
  data () {
    return {
      msg: 'Hi! I\'m a test'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
  @import "../scss/components/HelloWorld";
</style>
