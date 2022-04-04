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
    },
    UPDATE_TASK(state, task) {
      //Временный вариант.
      for(let listKey in state.lists) {
        for(let taskKey in state.lists[listKey].items) {
          if(state.lists[listKey].items[taskKey].id === task.id) {
            state.lists[listKey].items[taskKey] = task
          }
        }
      }
    }
  },
  actions: {
    loadLists(context, lists) {
      context.commit('LOAD_LISTS', lists)
    },
    async updateTask(context, task) {
      const {data} = await API.post(`api/auth/tasks/${task.id}/update`, {
        title: task.title,
        content: task.content
      })
      if(data) {
        //TODO: добавить поля на бэке и передавать тут data. Сделать отображение сообщений.
        context.commit('UPDATE_TASK', task)
        //this.$message.success("Изменения успешно сохранены!");
      }
    }
  },
  getters: {
    lists(state) {
      return state.lists
    }
  }
}
