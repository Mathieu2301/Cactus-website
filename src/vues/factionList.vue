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

      <table>
        <tr>
          <th
            v-for="(TH, id) in tableTH"
            :key="id"
            :class="TH.class"
            @click="setSortBy(TH.sortBy)"
          >{{ TH.text }}</th>
        </tr>

        <tr
          class="selectable"
          v-for="(faction) in factions"
          :key="faction.UID"
          @click="select(faction.UID)"
        >
          <td class="center">{{ faction.name }}</td>
          <td class="center">{{ faction.description }}</td>
          <td class="center">{{ faction.claims }}</td>
          <td class="center">{{ faction.money | bignbr }} €</td>
        </tr>
      </table>

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
    this.api.getFactions((factions) => {
      this.list_f = factions;
      this.loaded = true;
    });
  },

  computed: {

    factions() {
      let sorted = Object.values(this.list_f).sort((a, b) => {
        switch (this.sortBy) {
          case '_FACTION_NAME':
          case '_FACTION_DESC':
            return (a.name > b.name) * 2 - 1;

          default: return b[this.sortBy] - a[this.sortBy];
        }
      });

      if (this.sortInverse) sorted.reverse();

      if (this.search_keyword) {
        sorted = sorted.filter(v => v.name.toUpperCase().replace(/\W/g, '')
          .includes(this.search_keyword.toUpperCase().replace(/\W/g, ''))
          || v.description.toUpperCase().replace(/\W/g, '')
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
          text: 'Name',
          class: {
            center: true,
            sortable: true,
          },
          sortBy: '_FACTION_NAME',
        },
        {
          text: 'Description',
          class: {
            center: true,
            sortable: true,
          },
          sortBy: '_FACTION_DESC',
        },
        {
          text: 'Claims',
          class: {
            center: true,
            sortable: true,
          },
          sortBy: 'claims',
        },
        {
          text: 'Money',
          class: {
            center: true,
            sortable: true,
          },
          sortBy: 'money',
        },
      ],

      loaded: false,
      list_f: {},
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

    select(id) {
      this.$router.push(`/faction/${id}`);
    },
  },

};
</script>
