<template>
  <el-header><h2 class="page-header">Задачи</h2></el-header>
  <el-main>
    <el-row class="mb-4">
      <el-button type="primary">Добавить карточку</el-button>
    </el-row>
    <app-tasks-list />
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
        const response = await fetch('api/tasks/create', {
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
