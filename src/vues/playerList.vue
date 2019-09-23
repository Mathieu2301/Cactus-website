<template>
<div class="list">

    <div class="container" v-if="loaded">

      <input
        class="search_bar"
        type="text"
        name="keyword"
        placeholder="Rechercher..."
        v-model="search_keyword"
        spellcheck="false"
      >

      <table class="_desktop">
        <tr>
          <th
            v-for="(TH, id) in tableTH"
            :key="id"
            :class="TH.class"
            @click="setSortBy(TH.sortBy)"
          >{{ TH.text }}</th>
        </tr>

        <tr
          v-for="(player, id) in players"
          :key="id"
          :class="{
            selectable: true,
            text_opacity: player.status == 'OFFLINE'
          }"
          @click="select(player)"
        >
          <td class="center">
            <img
              class="table-icon"
              :src="'//minotar.net/avatar/' + player.name + '/32.png'"
              alt="Player head"
            >
          </td>
          <td class="center">{{ player.name }}</td>
          <td class="center">{{ player.money | bignbr }} €</td>
          <td class="center">{{ player.faction }}</td>
        </tr>
      </table>

      <div class="_mobile mobile_table">
        <div
          v-for="(player) in players"
          :key="player.name"
          :class="{
            item: true,
            text_opacity: player.status == 'OFFLINE'
          }"
          @click="select(player)"
        >
          <img :src="'//minotar.net/avatar/' + player.name + '/150.png'" alt="Player head">
          <div class="text">{{ player.name }}</div>
        </div>
      </div>

    </div>

    <loader v-else></loader>
  </div>
</template>

<script>
import components from '@/global';
import filters from '@/filters';

export default {
  components,
  filters,

  created() {
    this.api.getPlayers((players) => {
      this.list = players;
      this.loaded = true;
    });
  },

  computed: {

    players() {
      let sorted = Object.values(this.list).sort((a, b) => {
        switch (this.sortBy) {
          case '_PLAYER_NAME':
          case '_PLAYER_FACTION':
            return (a.name > b.name) * 2 - 1;

          default: return b[this.sortBy] - a[this.sortBy];
        }
      });

      if (this.sortInverse) sorted.reverse();

      if (this.search_keyword) {
        sorted = sorted.filter(v => v.faction.toUpperCase().replace(/\W/g, '')
          .includes(this.search_keyword.toUpperCase().replace(/\W/g, ''))
          || v.name.toUpperCase().replace(/\W/g, '')
            .includes(this.search_keyword.toUpperCase().replace(/\W/g, ''))
          || v.money.toString().includes(this.search_keyword));
      }

      return sorted;
    },

  },

  data() {
    return {
      tableTH: [
        {
          text: '',
          class: {},
          sortBy: '',
        },
        {
          text: 'Name',
          class: {
            center: true,
            sortable: true,
          },
          sortBy: '_PLAYER_NAME',
        },
        {
          text: 'Money',
          class: {
            center: true,
            sortable: true,
          },
          sortBy: 'money',
        },
        {
          text: 'Faction',
          class: {
            center: true,
            sortable: true,
          },
          sortBy: '_PLAYER_FACTION',
        },
      ],

      loaded: false,
      list: {},
      sort: false,
      sortBy: '_PLAYER_NAME',
      sortInverse: false,

      search_keyword: '',
    };
  },

  methods: {
    setSortBy(index) {
      if (index) {
        this.sort = true;
        if (index !== this.sortBy) {
          this.sortBy = index;
          this.sortInverse = false;
        } else this.sortInverse = !this.sortInverse;
      }
    },

    select(player) {
      this.$router.push(`/player/${player.name}`);
    },
  },

};
</script>

<style scoped>

</style>
