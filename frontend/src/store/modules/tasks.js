import axios from "axios";
import API from "../../utils/api";

export default {
  state() {
    return {
      lists: []
    }
  },
  mutations: {
    ADD_LIST(state, list) {
      state.lists.push(list)
    }
  },
  actions: {
  },
  getters: {
  }
}
