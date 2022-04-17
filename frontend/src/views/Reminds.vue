<template>
  <el-header><h2 class="page-header">Напоминания</h2></el-header>
  <el-main v-loading="loading">
    <el-table :data="this.$store.getters.reminds" style="width: 100%">
      <el-table-column type="expand">
        <template #default="props">
          <p>Title: {{ props.row.title }}</p>
          <p>Content: {{ props.row.content }}</p>
          <p>Date: {{ props.row.datetime }}</p>
        </template>
      </el-table-column>
      <el-table-column label="Title" prop="title" />
      <el-table-column label="Date" prop="datetime" />
    </el-table>
    <el-button
      type="primary"
      @click="showAddModal"
      circle
    >+</el-button>
  </el-main>
</template>
<script>
  import API from "../utils/api";

  export default {
    data() {
      return {
        loading: false,
      }
    },
    methods: {
      async loadData() {
        this.loading = true
        try {
          const {data} = await API.get('api/auth/reminds')
          if(!data) {
            throw new Error('Нет данных!')
          }
          const reminds = Object.keys(data.reminds).map(key => {
            return {
              id: key,
              ...data.reminds[key]
            }
          })
          this.$store.dispatch('loadReminds', reminds)
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
      showAddModal() {
        console.log(this.$store.getters.reminds)
      }
    },
    mounted() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>
