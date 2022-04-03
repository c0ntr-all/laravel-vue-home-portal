<template>
  <el-header><h2 class="page-header">Задачи</h2></el-header>
  <el-main>
    <el-space alignment="flex-start" v-loading="loading" wrap>
      <app-task-list
        v-for="list in this.$store.getters.lists"
        :listId="list.id"
        :listTitle="list.title"
        :items="list.items"
        :key="list.id"
      ></app-task-list>
      <app-list-create-button @listCreated="addList"></app-list-create-button>
    </el-space>
  </el-main>
</template>
<script>
  import AppTaskList from "../components/tasks/AppTaskList";
  import AppListCreateButton from "../components/tasks/AppListCreateButton";

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
          const lists = Object.keys(data.lists).map(key => {
            return {
              id: key,
              ...data.lists[key]
            }
          })
          this.$store.dispatch('loadLists', lists)
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
      addList(list) {
        this.$store.commit('ADD_LIST', list)
      }
    },
    components: {AppTaskList,AppListCreateButton},
    mounted() {
      this.loadData()
    },
  }
</script>
