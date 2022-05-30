<template>
  <el-header><h1 class="page-header">Загрузка музыки</h1></el-header>
  <el-main>
    <el-form>
      <el-row :gutter="10">
        <el-col :xs="24" :sm="12" :md="10" :lg="8" :xl="5">
          <div class="grid-content bg-purple"/>
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
          <el-button type="primary" @click="createRemind" round>Создать</el-button>
        </el-col>
        <el-col :xs="24" :sm="12" :md="10" :lg="8" :xl="5">
          <div class="grid-content bg-purple"/>
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
          <el-button type="primary" @click="createRemind" round>Создать</el-button>
        </el-col>
      </el-row>
    </el-form>
  </el-main>
</template>
<script>
  import API from "../../utils/api";

  export default {
    data() {
      return {
        model: {
          artist: {

          },
          album: {

          }
        }
      }
    },
    methods: {
      loadData() {

      }
    },
    mounted() {
      this.loadData();
    }
  }
</script>

<style lang="scss" scoped>
  .el-row {
    margin-bottom: 20px;
  }
  .el-row:last-child {
    margin-bottom: 0;
  }
  .el-col {
    border-radius: 4px;
  }

  .grid-content {
    border-radius: 4px;
    min-height: 36px;
  }
  .bg-purple {
    background: purple;
  }
</style>
