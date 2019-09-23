import Vue from 'vue';
import Router from 'vue-router';
import app from './app.vue';
import api from './api';
import homePage from './vues/homePage.vue';

import playerList from './vues/playerList.vue';
import player from './vues/player.vue';

import itemList from './vues/itemList.vue';
import item from './vues/item.vue';

import factionList from './vues/factionList.vue';
import faction from './vues/faction.vue';

Vue.config.productionTip = false;

Vue.use(Router);

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'Serveur Cactus',
      component: homePage,
    },
    {
      path: '/players',
      name: 'Liste des joueurs',
      component: playerList,
    },
    {
      path: '/player/:player_name',
      name: 'Joueur',
      component: player,
    },
    {
      path: '/factions',
      name: 'Liste des factions',
      component: factionList,
    },
    {
      path: '/faction/:faction_id',
      name: 'Faction',
      component: faction,
    },
    {
      path: '/items',
      name: 'Liste des items',
      component: itemList,
    },
    {
      path: '/item/:item_id',
      name: 'Item',
      component: item,
    },
    { path: '*', redirect: '/' },
  ],
});

Vue.prototype.api = api(`${window.location.protocol}//usp-3.fr/api/cactus`);

if (window.location.pathname === '/') {
  Vue.prototype.api.getPlayers();
  Vue.prototype.api.getFactions();
  Vue.prototype.api.getItems();
} else {
  setTimeout(() => {
    Vue.prototype.api.getPlayers();
    Vue.prototype.api.getFactions();
    Vue.prototype.api.getItems();
  }, 20000);
}


new Vue({
  router,
  render: h => h(app),
}).$mount('#app');
