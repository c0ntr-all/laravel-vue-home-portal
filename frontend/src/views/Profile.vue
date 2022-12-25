<template>
  <el-main>
    <h1 class="page-header">Профиль</h1>
    <div class="page-content">
      <p v-if="loading === false">Логин: {{ login }}</p>
      <el-skeleton v-if="loading !== false" style="width: 240px" animated>
        <template #template>
          <el-skeleton-item variant="text" style="margin-right: 16px" />
        </template>
      </el-skeleton>
    </div>
  </el-main>
</template>
<script>
  import API from "@/utils/api";

  export default {
    data() {
      return {
        login: '',
        loading: false
      }
    },
    methods: {
      loadData() {
        this.loading = true

        setTimeout(async() => {
          try {
            const {data} = await API.post('me')
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
