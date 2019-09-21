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
          class="selectable"
          v-for="(item) in items"
          :key="item.itemID"
          @click="select(item)"
        >
          <td class="center">
            <img :src="'/items/' + item.itemID + '-0.png'" alt="Item" style="margin: -10px 0">
          </td>
          <td class="center">{{ item.itemID | itemName }}</td>
          <td class="left">{{ item.price }}.00 €</td>
          <td class="left" :class="{
            text_red: item.price < item.firstPrice,
            text_green: item.price > item.firstPrice,
          }">
            {{
              (item.price >= item.firstPrice ? '+' : '')
            }}{{
              item.price - item.firstPrice
            }}.00 €

            ({{
              (item.price >= item.firstPrice ? '+' : '')
            }}{{
              item.price | evol(item.firstPrice)
            }}%)
          </td>
        </tr>
      </table>

      <div class="_mobile mobile_table">
        <div
          class="item"
          v-for="(item) in items"
          :key="item.itemID"
          @click="select(item)"
        >
          <img :src="'/items/' + item.itemID + '-0.png'" alt="Item">
          <div class="text">{{ item.price }}.00 €</div>
        </div>
      </div>

    </div>

    <loader v-else></loader>
  </div>
</template>

<style scoped>

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

</style>

<script>
import components from '@/global';
import filters from '@/filters';

export default {
  components,
  filters,

  created() {
    this.api.getList((list) => {
      this.list = list;
      this.loaded = true;
    });
  },

  computed: {

    items() {
      let sorted = Object.values(this.list).sort((a, b) => {
        switch (this.sortBy) {
          case '_ITEM_NAME':
            return (filters.itemName(a.itemID) > filters.itemName(b.itemID)) * 2 - 1;

          case '_ITEM_EVOL':
            return filters.evol(b.price, b.firstPrice) - filters.evol(a.price, a.firstPrice);

          default: return b[this.sortBy] - a[this.sortBy];
        }
      });

      if (this.sortInverse) sorted.reverse();

      if (this.search_keyword) {
        sorted = sorted.filter(v => v.itemID.toString() === (this.search_keyword)
          || filters.itemName(v.itemID).toUpperCase().replace(/\W/g, '')
            .includes(this.search_keyword.toUpperCase().replace(/\W/g, ''))
          || v.price.toString() === (this.search_keyword));
      }

      return sorted;
    },

  },

  data() {
    return {
      tableTH: [
        {
          text: 'ID',
          class: {
            center: true,
          },
          sortBy: 'itemID',
        },
        {
          text: 'Item',
          class: {
            center: true,
          },
          sortBy: '_ITEM_NAME',
        },
        {
          text: 'Prix',
          class: {
            left: true,
          },
          sortBy: 'price',
        },
        {
          text: 'Évolution',
          class: {
            left: true,
          },
          sortBy: '_ITEM_EVOL',
        },
      ],

      loaded: false,
      list: {},
      sort: false,
      sortBy: '_ITEM_NAME',
      sortInverse: false,

      search_keyword: '',
    };
  },

  methods: {
    setSortBy(index) {
      this.sort = true;
      if (index !== this.sortBy) {
        this.sortBy = index;
        this.sortInverse = false;
      } else this.sortInverse = !this.sortInverse;
    },

    select(item) {
      this.$router.push(`/item/${item.itemID}`);
    },
  },

};
</script>
