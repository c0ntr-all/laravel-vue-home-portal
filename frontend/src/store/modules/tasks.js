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
    },
    LOAD_LISTS(state, lists) {
      state.lists = lists
    }
  },
  actions: {
    loadLists(context, lists) {
      context.commit('LOAD_LISTS', lists)
    }
  },
  getters: {
    lists(state) {
      return state.lists
    }
  }
}
