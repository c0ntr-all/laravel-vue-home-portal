<template>
  <el-card class="task-list">
    <template #header>
      <div class="card-header">
        <span>{{ listTitle }}</span>
        <el-popover v-model:visible="visible" placement="left" :width="160">
          <p>Are you sure to delete this?</p>
          <div style="text-align: right; margin: 0">
            <el-button size="small" type="text" @click="visible = false">cancel</el-button>
            <el-button size="small" type="primary" @click="visible = false">confirm</el-button>
          </div>
          <template #reference>
            <el-button @click="visible = true" class="button" type="text">
              <el-icon :size="20"><edit /></el-icon>
            </el-button>
          </template>
        </el-popover>
      </div>
    </template>
    <app-task v-for="item in items" :key="item.id" :item="item"></app-task>
    <el-form @submit.prevent="createTask(listId)">
      <el-input placeholder="Введите заголовок!" v-model="newTaskTitle" />
    </el-form>
  </el-card>
</template>

<script setup>
  import {
    Edit,
  } from '@element-plus/icons-vue'
  import { ref } from 'vue'
</script>

<script>
  import API from '../../utils/api'

  import AppTask from './AppTask'
  import {ref} from "vue";

  export default {
    data() {
      return {
        newTaskTitle: '',
        visible: ref(false)
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
          this.items.push(data.items)
          this.newTaskTitle = ''
        }
      },
    },
    components: {AppTask}
  }
</script>

<style lang="scss" scoped>
  .task-list {
    width: 272px;
    background-color: #ebecf0;
    border-radius: 3px;
  }
</style>
