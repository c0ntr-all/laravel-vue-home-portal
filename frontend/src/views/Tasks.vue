<template>
  <el-header><h2 class="page-header">Задачи</h2></el-header>
  <el-main>
    <app-task-lists
      :lists="lists"
      v-loading="loading"
    />
  </el-main>
</template>
<script>
  import AppTaskLists from "../components/tasks/AppTaskLists";
  import API from '../utils/api'

  export default {
    data() {
      return {
        title: '',
        lists: [],
        loading: false
      }
    },
    methods: {
      loadData() {
        this.loading = true

        setTimeout(async() => {
          try {
            const {data} = await API.get('api/auth/tasks')
            if(!data) {
              throw new Error('Нет данных!')
            }
            this.lists = Object.keys(data.lists).map(key => {
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
        const response = await API.post('api/auth/tasks/create', {
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
