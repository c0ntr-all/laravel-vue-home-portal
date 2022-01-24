<template>
  <el-card class="box-card" v-if="show">
    <template #header>
      <div class="card-header">
        <el-form @submit.prevent="createList">
          <el-input placeholder="Введите заголовок!" v-model="newListTitle" />
        </el-form>
      </div>
    </template>
    <el-button type="primary">Добавить</el-button>
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
  export default {
    data() {
      return {
        show: 0,
        newListTitle: ''
      }
    },
    methods: {
      async createList() {
        const response = await fetch('api/tasks/list/store', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            title: this.newListTitle
          })
        })

        const data = await response.json()
        if(data) {
          console.log(data.result)
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
