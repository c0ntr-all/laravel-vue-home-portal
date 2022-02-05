<template>
  <el-header><h2 class="page-header">Профиль</h2></el-header>
  <el-main>
    <p>Логин: {{ login }}</p>
  </el-main>
</template>
<script>
  import API from "../utils/api";

  export default {
    data() {
      return {
        login: ''
      }
    },
    methods: {
      loadData() {
        this.loading = true

        setTimeout(async() => {
          try {
            const {data} = await API.post('api/auth/me')
            if(!data) {
              throw new Error('Нет данных!')
            }
            this.login = data.email
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
      }
    },
    mounted() {
      this.loadData()
    }
  }
</script>
<style>

</style>
