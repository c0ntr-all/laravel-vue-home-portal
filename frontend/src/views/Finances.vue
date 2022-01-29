<template>
  <el-header><h2 class="page-header">Финансы</h2></el-header>
  <el-main>
    <app-finances-list
      :data="data"
      @load="loadData"
      v-loading="loading"
      style="width: 100%; height: 100%"
    >
    </app-finances-list>
  </el-main>
</template>

<script>
  import AppFinancesList from "../components/finances/AppFinancesList"
  import API from '../utils/api'

  export default {
    data() {
      return {
        data: [],
        loading: false
      }
    },
    methods: {
      loadData() {
        this.loading = true

        setTimeout(async() => {
          try {
            const {data} = await API.get('api/auth/finances')
            if(!data) {
              throw new Error('Нет данных!')
            }
            this.data = Object.keys(data.finances).map(key => {
              return {
                id: key,
                ...data.finances[key]
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
      }
    },
    components: {AppFinancesList},
    mounted() {
      this.loadData()
    }
  }
</script>
