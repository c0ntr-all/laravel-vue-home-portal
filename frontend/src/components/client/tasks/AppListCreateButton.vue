<template>
  <el-card class="box-card" v-if="show">
    <template #header>
      <div class="card-header">
        <el-form @submit.prevent="createList">
          <el-input placeholder="Введите заголовок!" v-model="newListTitle" />
        </el-form>
      </div>
    </template>
    <el-button
      type="primary"
      @click="createList"
    >Добавить</el-button>
    <el-button
      type="danger"
      :icon="CloseBold"
      @click="show = false"
      circle
    ></el-button>
  </el-card>
  <el-button
    type="primary"
    @click="show = true"
    v-else
  >Добавить карточку</el-button>
</template>

<script>
  import API from '@/utils/api'

  export default {
    data() {
      return {
        show: 0,
        newListTitle: ''
      }
    },
    methods: {
      async createList() {
        const {data} = await API.put('tasks/list/store', {
          title: this.newListTitle
        })
        if(data) {
          this.$emit('listCreated', data.lists)
        }
      },
    }
  }
</script>
<script setup>
  import {
    CloseBold
  } from '@element-plus/icons-vue'
</script>

<style lang="scss" scoped>

</style>
