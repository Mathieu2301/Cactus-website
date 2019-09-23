<template>
  <div class="player">
    <div class="container" v-if="loaded">

      <div>
        <h1>Infos</h1>

        <table>
          <tr>
            <td class="left"><b>Nom</b></td>
            <td class="center">{{ player.name }}</td>
          </tr>
          <tr>
            <td class="left"><b>Money</b></td>
            <td class="center">{{ player.money | bignbr }} €</td>
          </tr>
          <tr>
            <td class="left"><b>Faction</b></td>
            <td class="center">
              <router-link :to="'/faction/' + player.factionID">{{ player.faction }}</router-link>
            </td>
          </tr>
          <tr>
            <td class="left"><b>Role</b></td>
            <td class="center">{{ player.role }}</td>
          </tr>
          <tr>
            <td class="left"><b>Power</b></td>
            <td class="center">{{ player.power }}</td>
          </tr>
          <tr>
            <td class="left"><b>Status</b></td>
            <td class="center">{{ player.status }}</td>
          </tr>
        </table>
      </div>

      <div>
        <h1>Trades</h1>

        <table>
          <tr>
            <th class="center">Item</th>
            <th class="center">Quantité</th>
            <th class="center">Date</th>
          </tr>
          <tr v-for="(trade, id) in player.trades" :key="id">
            <td class="center">
              <img class="table-icon" :src="'/items/' + trade.item_id + '-0.png'" alt="Item">
            </td>
            <td class="center" :class="{
              text_green: trade.type == 'SELL',
              text_red: trade.type == 'BUY',
            }">
              {{ (trade.type != 'BUY' ? '+' : '-') + trade.amount }}
            </td>
            <td class="center">{{ trade.date | fromNow }}</td>
          </tr>
        </table>
      </div>

    </div>
    <loader v-else></loader>
  </div>
</template>

<style scoped>

.container {
  display: flex;
  flex-wrap: wrap;
  box-sizing: border-box;
  padding: 40px;
  justify-content: space-around;
}

.container > * {
  width: 500px;
  max-width: 800px;
  margin: 0 20px;
}

</style>

<script>
import components from '@/global';
import filters from '@/filters';

export default {
  components,
  filters,

  data() {
    return {
      loaded: false,
      player: {},
    };
  },

  mounted() {
    this.api.getPlayer(this.$route.params.player_name, (player) => {
      this.player = player;

      this.player.trades = Object.values(player.trades);
      this.player.trades.reverse();

      this.loaded = true;
    });
  },

};
</script>
