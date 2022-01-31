<template>
  <div class="avatar">
    <el-popover placement="bottom" :width="200" trigger="click">
      <template #reference>
        <el-button>
          <span>Профиль</span>
          <el-icon class="el-icon--right">
            <arrow-down />
          </el-icon>
        </el-button>
      </template>
      <div class="popover-content">
        <el-menu class="el-menu-vertical" :router="true">
          <el-menu-item :index="'/profile'">
            <el-icon><icon-menu /></el-icon>
            <span>Профиль</span>
          </el-menu-item>
          <el-menu-item :index="'2'" @click="logout"><span>Выйти</span></el-menu-item>
        </el-menu>
      </div>
    </el-popover>
  </div>
</template>

<script setup>
  import {
    Menu as IconMenu,
    ArrowDown
  } from '@element-plus/icons-vue'
</script>
<script>
  import API from "../utils/api";

  export default {
    methods: {
      logout() {
        API.post('/api/auth/logout')
          .then(response => {
            localStorage.removeItem('access_token')
            this.$router.push({name: 'login'})
          })
      }
    }
  }
</script>

<style scoped>
  .avatar {
    display: flex;
    justify-content: flex-start;
    padding: 15px 20px;
  }
</style>
