<template>
  <el-header><h2 class="page-header">Напоминания</h2></el-header>
  <el-main v-loading="loading">
    <el-button
      type="primary"
      @click="openModal = true"
    >Добавить</el-button>
    <app-modal :openModal="openModal" @closeModal="openModal = false">
      <template v-slot:title>
        Создать напоминание
      </template>
      <template v-slot:content>
        <el-input
          v-model="this.model.title"
          maxlength="10"
          placeholder="Введите заголовок"
          show-word-limit
          type="text"
        />
        <el-input
          v-model="this.model.content"
          :rows="2"
          show-word-limit
          maxlength="1000"
          type="textarea"
          placeholder="Описание для напоминания..."
        />
      </template>
      <template v-slot:footer>
        <el-button type="primary" @click="createRemind" round>Создать</el-button>
      </template>
    </app-modal>
    <el-table :data="this.$store.getters.reminds" style="width: 100%">
      <el-table-column type="expand">
        <template #default="props">
          <p>Content: {{ props.row.content }}</p>
        </template>
      </el-table-column>
      <el-table-column label="Title" prop="title" />
      <el-table-column label="Date" prop="datetime" />
      <el-table-column label="Active" prop="isActive" />
    </el-table>
  </el-main>
</template>
<script>
  import API from "../utils/api";

  import AppModal from '../components/default/AppModal'

  export default {
    data() {
      return {
        loading: false,
        openModal: false,
        model: {
          title: null,
          content: null
        }
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
      createRemind() {

      }
    },
    components: {
      AppModal
    },
    mounted() {
      this.loadData();
    }
  }
</script>

<style lang="scss" scoped>

</style>
