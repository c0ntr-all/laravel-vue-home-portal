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
        title: '',
        data: [],
        loading: false
      }
    },
    methods: {
      loadData() {
        this.loading = true

        setTimeout(async() => {
          try {
            const {data} = await axios.get('api/tasks')
            if(!data) {
              throw new Error('Нет данных!')
            }
            this.data = Object.keys(data.tasks).map(key => {
              return {
                id: key,
                ...data.tasks[key]
              }
            })
            this.loading = false
          }catch(e) {
            this.alert = {
              type: 'danger',
              title: 'Ошибка',
              text: e.message
            }
            this.loading = false
          }
        })
      },
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
