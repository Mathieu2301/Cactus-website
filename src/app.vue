<template>
  <div id="app">
    <cdc-header></cdc-header>
    <transition :name="transitionName">
      <router-view/>
    </transition>
  </div>
</template>

<style>
:root {
  --color0: #000;
  --color1: #FFF;
  --color2: #fafdff;
  --color5: #131e29;
  --color6: #2d4252;
  --color7: #455f73;
  --color8: #049467;
  --color9: #00b176;

  --red:    #ff4e4e;
}

body {
  margin: 0;
  background-color: var(--color6);
}

body * {
  transition-duration: .3s;
  box-sizing: border-box;
}

#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: var(--color2);
}

.clickable, .selectable { cursor: pointer }
.center { text-align:center !important }

a {
  cursor: pointer;
  text-decoration: underline;
  color: var(--color9);
}

.status {
  color: #2c3e50;
  padding: 6px;
  width: 80px;
  text-align: center;
  line-height: 1;
  box-shadow: 1px 3px 16px -8px var(--color5);
}

.text_green  { color: var(--color9) }
.text_red    { color: var(--red) }


.slide-left-enter-active,
.slide-left-leave-active,
.slide-right-enter-active,
.slide-right-leave-active {
  transition-duration: .3s;
  transition-property: height, opacity, transform;
  transition-timing-function: cubic-bezier(0.55, 0, 0.1, 1);
  position: fixed;
  overflow: hidden;
}

.slide-left-enter,
.slide-right-leave-active {
  opacity: 0;
  transform: translate(65%, 0);
}

.slide-left-leave-active,
.slide-right-enter {
  opacity: 0;
  transform: translate(-65%, 0);
}

.middle {
  position: absolute;
  --width: 900px;
  top: 30%;
  left: calc(50% - (var(--width)/2));
  width: var(--width);
}

table {
  border-collapse: collapse;
  width: 100%;
  box-sizing: border-box;
  font-size: 18px;
  line-height: 40px;
}

th { cursor: pointer }
th:hover {
  color: var(--color8);
}

th, td {
  padding: 8px;
  text-align: left;
}

tr {
  border-bottom: 1px solid var(--color7);
}

.selectable:hover {
  border-bottom: 1px solid var(--color9);
}

@media screen and (max-width: 599px) {
  ._desktop { display: none !important }
  .negligible { display: none }
}

@media screen and (min-width: 600px) {
  ._mobile { display: none !important }
}

</style>

<script>
import components from '@/global';

export default {
  components,
  computed: {
    transitionName() {
      return `slide-${['/'].includes(this.$route.path) ? 'right' : 'left'}`;
    },
  },
};
</script>
