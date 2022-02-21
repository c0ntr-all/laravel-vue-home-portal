<template>
  <el-card class="task-list">
    <template #header>
      <div class="card-header">
        <span>{{ listTitle }}</span>
        <el-button class="button" type="text">
          <el-icon :size="20"><edit /></el-icon>
        </el-button>
      </div>
    </template>
    <div class="text task" v-for="item in items">
      <a href="#" class="task__link" @click.prevent="openTask">{{ item.title }}</a>
    </div>
    <el-form @submit.prevent="createTask">
      <el-input placeholder="Введите заголовок!" v-model="newTaskTitle" />
    </el-form>
  </el-card>
</template>

<script setup>
  import {
    Edit,
  } from '@element-plus/icons-vue'
</script>

<script>
  import API from '../../utils/api'

  export default {
    data() {
      return {
        newTaskTitle: '',
      }
    },
    props: {
      listTitle: String,
      items: Array,
    },
    methods: {
      async createTask() {
        console.log(this.newListTitle)
        const {data} = await API.post('api/auth/tasks/store')
        // const response = await fetch('api/tasks/list/store', {
        //   method: 'POST',
        //   headers: {
        //     'Content-Type': 'application/json'
        //   },
        //   body: JSON.stringify({
        //     title: this.newListTitle
        //   })
        })

        const data = await response.json()
        if(data) {
          console.log(data.result)
        }
      },
      openTask() {
        alert('open task')
      }
    }
  }
</script>

<style lang="scss" scoped>
  .task-list {
    background-color: #ebecf0;
    border-radius: 3px;
  }
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .text {
    font-size: 14px;
  }
  .task {
    margin-bottom: 8px;

    &__link {
      display: block;
      padding: 8px;
      background-color: #fff;
      text-decoration: none;
      color: #000;
      border-radius: 3px;
      box-shadow: 0 1px 0 #091e4240;

      &:hover {
        background-color: #f4f5f7;
        border-bottom-color: #091e4240;
      }
    }
  }
</style>
