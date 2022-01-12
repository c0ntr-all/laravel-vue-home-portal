<template>
  <el-header><h2 class="page-header">Финансы</h2></el-header>
  <el-main>
    <app-preloader v-if="loading"></app-preloader>
    <app-finances-list
      v-else
      :data="data"
      @load="loadData"
    >
    </app-finances-list>
  </el-main>
</template>

<script>
  import AppFinancesList from "../components/finances/AppFinancesList"
  import AppPreloader from '../components/default/AppPreloader'
  import axios from 'axios'

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
            const {data} = await axios.get('api/finances')
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
    components: {AppFinancesList,AppPreloader},
    mounted() {
      this.loadData()
    }
  }
</script>
