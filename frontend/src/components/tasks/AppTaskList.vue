<template>
  <el-space alignment="flex-start" wrap>
    <app-task
      v-for="list in data"
      :name="list.title"
      :items="list.items"
    ></app-task>
    <app-task-create-button></app-task-create-button>
  </el-space>
</template>

<script>
  import AppTask from "./AppTask";
  import AppTaskCreateButton from "./AppTaskCreateButton";

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
    components: {AppTask,AppTaskCreateButton}
  }
</script>

<style lang="scss" scoped>

</style>
