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
    },
    CREATE_TASK(state, task) {
      state.lists.find(item => task.list_id === item.id).items.push(task)
    },
    CREATE_TASK_COMMENT(state, comment) {
      state.lists.find(item => task.list_id === item.id).items.push(task)
    },
  },
  actions: {
    loadLists(context, lists) {
      context.commit('LOAD_LISTS', lists)
    },
    async updateTask(context, task) {
      const {data} = await API.post(`tasks/${task.id}/update`, {
        title: task.title,
        content: task.content
      })
      if(data) {
        context.commit('UPDATE_TASK', data.items)
      }
    },
    async createTask(context, newList) {
      const {data} = await API.put('tasks/store/' + newList.list_id, {
        'title': newList.title
      })
      if(data) {
        context.commit('CREATE_TASK', data.items)
        return 'test'
      }
    },
    async editTaskContent(context, task) {
      const {data} = await API.patch(`tasks/${task.id}/update`, {
        content: task.content
      })
      if(data) {
        context.commit('UPDATE_TASK', data.items)
      }
    },
    async editTaskTitle(context, task) {
      const {data} = await API.patch(`tasks/${task.id}/update`, {
        title: task.title
      })
      if(data) {
        context.commit('UPDATE_TASK', data.items)
      }
    },
    async createTaskComment(context, comment) {
      const {data} = await API.post('tasks/comment/store', {
        'comment': item.comment,
        'taskId': item.id,
      })
      if(data) {
        context.commit('CREATE_TASK_COMMENT', data.comments)
      }
    },
  },
  getters: {
    lists(state) {
      return state.lists
    }
  }
}
