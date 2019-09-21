import * as moment from 'moment';
import defineFR from './moment-fr';
import items from '@/items.json';

defineFR(moment);

export default {
  addZeros: str => (parseInt(str, 10) < 10 ? `0${str}` : str),
  fromNow: date => moment(date).add(2, 'hours').fromNow(),
  formatDate: date => `${this.addZeros(date.getDate())}/${this.addZeros(date.getMonth() - 1)} à ${this.addZeros(date.getHours())}h${this.addZeros(date.getMinutes())}`,
  itemName: id => items.find(value => value.type === parseInt(id, 10)).name,
  evol: (price, firstPrice) => 0 - Math.round(firstPrice / price * 100 - 100),
  memory: val => Math.round(val / 100000) / 10,
  uppercasefirst: val => val[0].toUpperCase() + val.slice(1),
};
