import 'izitoast/dist/css/iziToast.min.css';
import izitoast from 'izitoast';

const nullf = () => null;

const µ = {
  request(url, callback = rs => rs, type = 'GET') {
    const xhr = new XMLHttpRequest();
    xhr.open(type, url, true);
    xhr.onreadystatechange = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        let rs;
        try {
          rs = JSON.parse(xhr.responseText);
        } catch (e) {
          console.log(xhr.responseText);
          izitoast.error({ title: 'Error', message: "Can't parse server response" });
          return;
        }

        if (!rs.error) {
          callback(rs);
        } else {
          let message = "L'item n'existe pas";
          // eslint-disable-next-line
          try { message = rs.error.message; } catch (e) {}
          izitoast.error({ title: 'Error', message });
        }
      }
    };
    xhr.send();
  },

  get(url, callback = rs => rs) {
    this.request(url, callback, 'GET');
  },
};

const data = {};

export default function (url) {
  return {

    getPlayers(callback = nullf) {
      if (!data.players) {
        µ.get(`${url}/?getPlayers`, (rs) => {
          data.players = rs;
          callback(rs);
        });
      } else callback(data.players);
    },

    getPlayer(name, callback) {
      µ.get(`${url}/?getPlayer=${name}`, callback);
    },

    getFactions(callback = nullf) {
      if (!data.factions) {
        µ.get(`${url}/?getFactions`, (rs) => {
          data.factions = rs;
          callback(rs);
        });
      } else callback(data.factions);
    },

    getFaction(factionUID, callback) {
      µ.get(`${url}/?getFaction=${factionUID}`, callback);
    },

    getItems(callback = nullf) {
      if (!data.items) {
        µ.get(`${url}/?getItems`, (rs) => {
          data.items = rs;
          callback(rs);
        });
      } else callback(data.items);
    },

    getItem(itemID, callback) {
      µ.get(`${url}/?getItem=${itemID}`, callback);
    },

  };
}
