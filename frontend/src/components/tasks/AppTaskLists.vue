<template>
  <el-space alignment="flex-start" wrap>
    <app-task
      v-for="list in data"
      :id="list.id"
      :name="list.title"
      :items="list.items"
    ></app-task>
    <app-list-create-button></app-list-create-button>
  </el-space>
</template>

<script>
  import AppTask from "./AppTask";
  import AppListCreateButton from "./AppListCreateButton";

  export default {
    emits: ['load'],
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
    components: {AppTask,AppListCreateButton}
  }
</script>

<style lang="scss" scoped>

</style>
