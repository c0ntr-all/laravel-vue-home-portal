<template>
  <el-header><h2 class="page-header">Задачи</h2></el-header>
  <el-main>
    <el-space alignment="flex-start" v-loading="loading" wrap>
      <app-task-list
        v-for="(list, index) in this.$store.getters.lists"
        :list="list"
        :items="list.items"
        :key="list.id"
        :isActive="activeList === index"
        @onTitleEdit="editListTitle(index)"
        :ref="'list-ref-' + index"
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
        loading: false,
        activeList: null
      }
    },
    methods: {
      async loadData() {
        this.loading = true
        try {
          const {data} = await API.get('tasks')
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
      },
      editListTitle(index) {
        if (this.activeList === index) {
          this.activeList = null;
        } else {
          this.activeList = index;
        }
      },
      clickOutside(e) {
        this.$store.getters.lists.forEach((list, index) => {
          let el = this.$refs['list-ref-' + index]
          return
          let textarea = (this.$refs['list-ref-' + index]).querySelector('textarea');
          let target = e.target;
          if (el !== target && !el.contains(target) && textarea !== target){
            this.activeList = null
          }
        })
      }
    },
    components: {AppTaskList,AppListCreateButton},
    mounted() {
      this.loadData()
      // document.addEventListener('click', this.clickOutside)
    },
    created(){
      document.addEventListener('click', this.clickOutside)
    },
    destroyed () {
      document.removeEventListener('click', this.clickOutside)
    },
  }
</script>
