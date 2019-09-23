<template>
  <div class="faction">
    <div class="container" v-if="loaded">

      <div style="width: 400px">
        <h1>Infos</h1>

        <table>
          <tr>
            <td class="left"><b>Nom</b></td>
            <td class="left">{{ faction.name }}</td>
          </tr>
          <tr>
            <td class="left"><b>Description</b></td>
            <td class="left">{{ faction.description }}</td>
          </tr>
          <tr>
            <td class="left"><b>Money</b></td>
            <td class="left">{{ faction.money | bignbr }} €</td>
          </tr>
          <tr>
            <td class="left"><b>Power</b></td>
            <td class="left">{{ faction.power }}</td>
          </tr>
          <tr>
            <td class="left"><b>Claims</b></td>
            <td class="left">{{ faction.claims }}</td>
          </tr>
        </table>
      </div>

      <div style="width: 560px">
        <h1>Membres</h1>

        <table>
          <tr>
            <!-- <th class="left"></th> -->
            <th class="center">Nom</th>
            <th class="center">Power</th>
            <th class="center">Money</th>
            <th class="center">Role</th>
          </tr>
          <tr v-for="(member, name) in faction.members" :key="name" :class="{
            selectable: true,
            text_opacity: member.status == 'OFFLINE'
          }" @click="selectPlayer(name)">
            <td class="left">
              <img
                class="table-icon"
                :src="'//minotar.net/avatar/' + name + '/32.png'"
                alt="Player head"
              >
              {{ name }}
            </td>
            <!-- <td class="center"></td> -->
            <td class="center">{{ member.power }}</td>
            <td class="center">{{ member.money | bignbr }} €</td>
            <td class="center">{{ member.role }}</td>
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
  margin: 0 20px;
}

.table-icon {
  margin-right: 7px;
}

</style>

<script>
import components from '@/global';
import filters from '@/filters';

export default {
  components,
  filters,

  methods: {

    selectPlayer(playerName) {
      this.$router.push(`/player/${playerName}`);
    },

  },

  data() {
    return {
      loaded: false,
      faction: {},
    };
  },

  mounted() {
    this.api.getFaction(this.$route.params.faction_id, (faction) => {
      this.faction = faction;

      this.loaded = true;
    });
  },

};
</script>
