<template>
  <el-space alignment="flex-start" wrap>
    <app-task-list
      v-for="list in data"
      :id="list.id"
      :name="list.title"
      :items="list.items"
    ></app-task-list>
    <app-list-create-button></app-list-create-button>
  </el-space>
</template>

<script>
  import AppTaskList from "./AppTaskList";
  import AppListCreateButton from "./AppListCreateButton";

  export default {
    props: ['data'],
    data() {
      return {
        tasks: []
      }
    },
    methods: {
      async addTask() {
        const response = await fetch('api/tasks/create', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            title: this.title
          })
        })

        const data = await response.json()
        this.tasks.push({
          title: this.title,
        })

        this.title = ''
      }
    },
    components: {AppTaskList,AppListCreateButton}
  }
</script>

<style lang="scss" scoped>

</style>
