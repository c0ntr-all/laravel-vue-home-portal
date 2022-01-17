<template>
  <el-space alignment="flex-start" wrap>
    <app-task
      v-for="list in data"
      :name="list.title"
    ></app-task>
  </el-space>
</template>

<script>
  import AppTask from "./AppTask";
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
    components: {AppTask}
  }
</script>

<style lang="scss" scoped>

</style>
