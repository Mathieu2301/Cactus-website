<template>
  <div class="item">
    <canvas class="container" id="chart" v-show="loaded"></canvas>

    <table class="container" v-if="loaded">
      <tr>
        <th class="center">Player</th>
        <th class="center negligible">Quantité</th>
        <th class="center">Trade</th>
        <th class="center">Date</th>
      </tr>

      <tr v-for="(trade, id) in trades" :key="id">
        <td class="center">{{ trade.player }}</td>
        <td class="center negligible">{{ trade.amount }}</td>
        <td :class="{
          center: true,
          text_red: (trades[id+1] && trades[id+1].price > trade.price),
          text_green: (trades[id+1] && trades[id+1].price < trade.price),
        }">{{ trade.price }}.00 € ({{
          (trades[id+1] && trades[id+1].price > trade.price ? '' : '+' )
          + (trades[id+1] ? trade.price - trades[id+1].price : '0')
          }}.00 €)
        </td>
        <td class="center">{{ trade.date | fromNow }}</td>
      </tr>
    </table>
    <loader v-else></loader>
  </div>
</template>

<style scoped>

.item {
  display: flex;
  flex-wrap: wrap;
  box-sizing: border-box;
  padding: 0 40px;
  align-items: center;
  justify-content: space-around;
}

.item > .container {
  width: 100%;
  max-width: 800px;
  margin: 0 20px;
}

@media screen and (min-width: 1350px){
  .item {
    flex-wrap: nowrap;
  }
}

#chart { margin: 50px 0 }
</style>

<script>
import ChartJS from 'chart.js';
import components from '@/global';
import filters from '@/filters';

export default {
  components,
  filters,

  data() {
    return {
      loaded: false,
      chart: null,
      trades: [],
    };
  },

  mounted() {
    this.api.getItem(this.$route.params.id, (item) => {
      this.trades = Object.values(item.trades);
      this.trades.reverse();

      const prices = [];

      Object.values(item.trades).forEach((trade) => {
        prices.push({
          t: new Date(trade.date),
          y: trade.price,
        });
      });

      this.chart = new ChartJS(document.getElementById('chart').getContext('2d'), {
        type: 'bar',
        data: {
          datasets: [
            {
              type: 'line',
              label: 'Prix',
              borderColor: '#00b176',
              borderWidth: 2,
              data: prices,
              pointRadius: 0,
            },
          ],
        },
        options: {
          responsive: true,
          legend: { display: false },
          elements: {
            line: {
              tension: 0,
            },
          },
          scales: {
            xAxes: [{
              display: false,
              type: 'time',
              distribution: 'series',
            }],
            yAxes: [{
              distribution: 'series',
              display: true,
            }],
          },
          tooltips: {
            mode: 'index',
            intersect: true,
          },
        },
      });

      this.loaded = true;
    });
  },

};
</script>
