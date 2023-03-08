<template>
  <q-page class="q-pa-lg" v-if="loading">
    <TasksPageSkeleton />
  </q-page>
  <q-page class="q-pa-lg" v-else>
    <q-btn
      v-if="showAddForm === false"
      @click="openAddForm"
      color="primary"
      icon="add"
      label="Добавить список"
      class="q-mb-lg"
      no-caps
    />
    <div v-if="showAddForm === true" class="list__add-form q-mb-lg">
      <q-input
        @keyup.enter="addNewList"
        v-model="model.newListName"
        ref="listAddTextarea"
        class="list__add-input q-mb-sm"
        dense
        outlined
      />
      <q-btn @click="addNewList" label="Добавить список" color="primary" class="q-mr-sm" no-caps />
      <q-btn @click="showAddForm = false" icon="close" color="danger" size="md" flat round dense />
    </div>
    <div class="task-lists row items-start q-gutter-md q-mb-lg">
      <TaskList
        v-for="(list, index) in taskLists"
        :list="list"
        :items="list.items"
        :key="list.id"
        :ref="'list-ref-' + index"
      />
    </div>
  </q-page>
</template>
<script>
import {ref, onMounted, nextTick} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

import TasksPageSkeleton from 'src/components/client/tasks/skeleton/TasksPage.vue'
import TaskList from "components/client/tasks/TaskList.vue"

export default {
  components: { TasksPageSkeleton, TaskList },
  setup() {
    const $q = useQuasar()

    const showAddForm = ref(false)
    const taskLists = ref([])
    let loading = ref(true)
    const listAddTextarea = ref(null)
    const model = ref({
      newListName: '',
    })

    const openAddForm = () => {
      showAddForm.value = true
      nextTick(() => {
        listAddTextarea.value.focus()
      })
    }
    const addNewList = async () => {
      const listName = model.value.newListName
      model.value.newListName = ''

      await API.put('tasks/list/store', {
        'title': listName
      }).then(response => {
        $q.notify({
          type: 'positive',
          message: 'Список успешно добавлен!'
        })
        taskLists.value.push(response.data.lists)
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }
    const getTaskLists = async () => {
      await API.get('tasks').then(response => {
        taskLists.value = response.data.lists
        loading.value = false
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error}`
        })
        loading.value = false
      })
    }
    onMounted(() => {
      getTaskLists()
    })
    return {
      showAddForm,
      model,
      loading,
      taskLists,
      listAddTextarea,
      openAddForm,
      addNewList
    }
  }
}
</script>
<style lang="scss" scoped>
.list {
  &__add-button {
    display: flex;
    align-items: center;
    border-radius: 3px;
    padding: 5px 0;

    &:hover {
      cursor: pointer;
      background-color: #091e4214;
    }
  }
  &__add-input {
    max-width: 300px;
  }
}
</style>
