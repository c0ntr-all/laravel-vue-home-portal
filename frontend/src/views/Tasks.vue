<template>
  <el-header><h2 class="page-header">Задачи</h2></el-header>
  <el-main>
    <app-tasks-list>

    </app-tasks-list>
  </el-main>
</template>
<script>
  import AppTasksList from "../components/tasks/AppTasksList";
  import axios from 'axios'
  export default {
    data() {
      return {
        title: ''
      }
    },
    methods: {
      async createList() {
        const response = await fetch('api/tasks', {
          methods: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            title: this.title
          })
        })
        const data = await response.json()
      }
    },
    components: {AppTasksList}
  }
</script>
