<template>
  <el-header><h2 class="page-header">Напоминания</h2></el-header>
  <el-main>
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
        alert('test')
      }
    },
    mounted() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>
