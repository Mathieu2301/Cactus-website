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

.text_opacity  { opacity: 0.5 }
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

th.sortable { cursor: pointer }
th.sortable:hover {
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

.list {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  box-sizing: border-box;
  padding: 40px;
  align-items: center;
}

.list > .container {
  width: 100%;
  max-width: 800px;
}

.search_bar {
  display: block;
  width: 100%;
  max-width: 800px;

  line-height: 40px;
  font-size: 18px;
  padding: 5px 15px;
  margin-bottom: 15px;

  border: none;
  border-radius: 10px;

  background-color: var(--color7);
  color: var(--color8);
  caret-color: var(--color8);
  outline-style: none;
}

.mobile_table {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-evenly;
}

.mobile_table > .item {
  width: 100px;
  vertical-align: middle;
  cursor: pointer;
}

.mobile_table > .item > img {
  width: 40%;
  vertical-align: middle;
  margin: 20px 0 9px;
}

.mobile_table > .item > * {
  margin: 5px 0;
  font-size: 18px;
}

.table-icon { margin: -10px 0px }

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
