<template>
  <div class="home">
    <div class="container" v-if="loaded">

    </div>

    <loader v-else></loader>
  </div>
</template>

<script>
import components from '@/global';

export default {
  components,

  data() {
    return {
      loaded: false,
    };
  },
};
</script>

<style>

</style>
