<template>
  <header>
    <svg class="homebutton" :class="{
      turn: $route.path=='/'
    }" @click="navigate" viewBox="0 0 24 24">
    <g><path d="M8.92893219,12 L16.7071068,19.7781746 C17.0976311,20.1686989 17.0976311,
      20.8018639 16.7071068,21.1923882 C16.3165825,21.5829124 15.6834175,
      21.5829124 15.2928932,21.1923882 L6.80761184,12.7071068 C6.41708755,
      12.3165825 6.41708755,11.6834175 6.80761184,11.2928932 L15.2928932,
      2.80761184 C15.6834175,2.41708755 16.3165825,2.41708755 16.7071068,
      2.80761184 C17.0976311,3.19813614 17.0976311,3.83130112 16.7071068,
      4.22182541 L8.92893219,12 Z"></path>
    </g></svg>

    <div class="title" v-if="$route.params.item_id">
      <img :src="'/items/' + $route.params.item_id + '-0.png'" alt="Item" style="margin: -5px 5px">
      {{ $route.params.item_id | itemName }}
    </div>

    <div class="title" v-else-if="$route.params.player_name">
      <img
        :src="'//minotar.net/avatar/' + $route.params.player_name + '/32'"
        alt="Player head"
        style="margin: -5px 5px"
      >
      {{ $route.params.player_name }}
    </div>

    <!-- <div class="title" v-else-if="$route.params.faction_id">
      {{ $route.params.faction_id | factionName }}
    </div> -->

    <div class="title" v-else>{{ $route.name }}</div>

    <div class="navbar">
      <router-link
        tag="div"
        v-for="navbtn in nav"
        :key="navbtn.path"
        :to="navbtn.path"
        :class="{
          nav: true,
          activ: $route.path == navbtn.path
        }"
      >
        {{ navbtn.text }}
      </router-link>
    </div>

  </header>
</template>

<script>
import filters from '@/filters';

export default {
  filters,

  data() {
    return {
      nav: [
        {
          text: 'Accueil',
          path: '/',
        },
        {
          text: 'Items',
          path: '/items',
        },
        {
          text: 'Joueurs',
          path: '/players',
        },
        {
          text: 'Factions',
          path: '/factions',
        },
      ],
    };
  },

  methods: {
    navigate() {
      if (this.$route.path !== '/') {
        this.$router.push('/');
      } else {
        this.$router.go(-1);
      }
    },
  },
};
</script>

<style scoped>
header {
  background-color: var(--color8);
  display: flex;
  flex-wrap: wrap;
  text-align: left;
  z-index: 1000;
}

.navbar {
  width: 100%;
  background-color: var(--color7);
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
}

.nav {
  align-self: center;
  cursor: pointer;
  text-align: center;
  padding: 15px;
  opacity: 0.5;
}

@media screen and (max-width: 400px) {
  .nav { width: 50% }
}

.nav.activ,
.nav:hover {
  opacity: 0.8;
}

.homebutton {
  fill: var(--color6);
  cursor: pointer;
  margin: 15px;
  width: 40px;
}

.title {
  font-size: 30px;
  align-self: center;
  margin-right: 10px;
  text-shadow: 1px 2px 4px var(--color7);
}
</style>
