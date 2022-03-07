<template>
  <el-header><h2 class="page-header">Задачи</h2></el-header>
  <el-main>
    <app-task-lists
      :lists="this.$store.state.lists"
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
        loading: false
      }
    },
    methods: {
      async loadData() {
        this.loading = true
        try {
          const {data} = await API.get('api/auth/tasks')
          if(!data) {
            throw new Error('Нет данных!')
          }
          this.$store.state.lists = Object.keys(data.lists).map(key => {
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
