<template>
  <el-header><h2 class="page-header">Задачи</h2></el-header>
  <el-main>
    <app-task-lists
      :data="data"
      @load="loadData"
      v-loading="loading"
    />
  </el-main>
</template>
<script>
  import AppTaskLists from "../components/tasks/AppTaskLists";
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
            this.data = Object.keys(data.lists).map(key => {
              return {
                id: key,
                ...data.lists[key]
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
    components: {AppTaskLists},
    mounted() {
      this.loadData()
    }
  }
</script>
