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
        <el-date-picker
          v-model="this.model.datetime"
          type="datetime"
          placeholder="Выберите время"
          format="DD.MM.YYYY HH:mm:ss"
          value-format="YYYY-MM-DD HH:mm:ss"
        />
      </template>
      <template v-slot:footer>
        <el-button type="primary" @click="createRemind" round>Создать</el-button>
      </template>
    </app-modal>
    <el-table
      :data="this.$store.getters.reminds"
      :default-sort="{ prop: 'isActive', order: 'descending' }"
      style="width: 100%"
    >
      <el-table-column type="expand">
        <template #default="props">
          <p>Content: {{ props.row.content }}</p>
        </template>
      </el-table-column>
      <el-table-column label="Title" prop="title" />
      <el-table-column label="Date" prop="datetime" sortable />
      <el-table-column label="Active" prop="isActive">
        <template #default="scope">
          <el-switch @click="switchActive(scope.row)" v-model="scope.row.isActive" />
        </template>
      </el-table-column>
    </el-table>
  </el-main>
</template>
<script>
  import { ref } from 'vue'
  import API from "../utils/api";

  import AppModal from '../components/default/AppModal'

  export default {
    data() {
      return {
        loading: false,
        openModal: false,
        model: {
          title: null,
          content: null,
          datetime: null
        }
      }
    },
    methods: {
      async loadData() {
        this.loading = true
        try {
          const {data} = await API.get('reminds')
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
        //TODO: Сделать человеческую обработку результата и ошибок из запроса
        this.$store.dispatch('createRemind', {
          title: this.model.title,
          content: this.model.content,
          datetime: this.model.datetime,
        }).then(result => {
          this.$message.success("Напоминание успешно добавлена!");
        }).catch(error => {
          this.$message.error(error);
        })

        this.model.title = ''
        this.model.content = ''
        this.model.datetime = ''
      },
      switchActive(remind) {
        this.$store.dispatch('switchActive', remind)
        .then(result => {
          this.$message.success("Статус напоминания изменен!");
        }).catch(error => {
          this.$message.error(error);
        })
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
