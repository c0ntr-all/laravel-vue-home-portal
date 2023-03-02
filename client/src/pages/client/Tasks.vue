<template>
  <q-page class="q-pa-lg" v-if="loading">
    <TasksPageSkeleton />
  </q-page>
  <q-page class="q-pa-lg" v-else>
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
import {ref, onMounted} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

import TasksPageSkeleton from 'src/components/client/tasks/skeleton/TasksPage.vue'
import TaskList from "components/client/tasks/TaskList.vue"

export default {
  components: { TasksPageSkeleton, TaskList },
  setup() {
    const $q = useQuasar()

    const taskLists = ref([])
    let loading = ref(true)

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
      loading,
      taskLists
    }
  }
}
</script>
