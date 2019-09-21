import 'izitoast/dist/css/iziToast.min.css';
import izitoast from 'izitoast';

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

let list = null;

export default function (url) {
  return {

    getList(callback) {
      if (!list) {
        µ.get(`${url}/?getAll`, (rs) => {
          list = rs;
          callback(rs);
        });
      } else callback(list);
    },

    getItem(itemID, callback) {
      µ.get(`${url}/?get=${itemID}`, callback);
    },

  };
}
