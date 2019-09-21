import Vue from 'vue';
import Router from 'vue-router';
import app from './app.vue';
import api from './api';
import itemList from './vues/itemList.vue';
import item from './vues/item.vue';

Vue.config.productionTip = false;

Vue.use(Router);

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'Cours du Cactus',
      component: itemList,
    },
    {
      path: '/item/:id',
      name: 'Item',
      component: item,
    },
    { path: '*', redirect: '/' },
  ],
});

Vue.prototype.api = api(`${window.location.protocol}//usp-3.fr/api/cactus`);

new Vue({
  router,
  render: h => h(app),
}).$mount('#app');
