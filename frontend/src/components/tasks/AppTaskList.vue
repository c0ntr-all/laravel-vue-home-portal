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
    <el-form @submit.prevent="createTask(listId)">
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
      listId: Number,
      listTitle: String,
      items: Array,
    },
    methods: {
      async createTask(listId) {
        const {data} = await API.put('api/auth/tasks/store/' + listId, {
          'title': this.newTaskTitle
        })
        if(data) {
          console.log(data.items)
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
