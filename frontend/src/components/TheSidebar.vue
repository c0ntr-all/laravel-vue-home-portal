<template>
  <el-aside width="200px">
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
            <el-menu-item :index="1">
              <el-icon><icon-menu /></el-icon>
              <span>Настройки</span>
            </el-menu-item>
            <el-menu-item :index="2">
              <el-icon><icon-menu /></el-icon>
              <span>Выйти</span>
            </el-menu-item>
          </el-menu>
        </div>
      </el-popover>
    </div>
    <el-menu class="el-menu-vertical" :router="true" :default-active="$route.path">
      <template v-for="item in $router.options.routes">
        <el-menu-item :index="item.path">
          <el-icon><icon-menu /></el-icon>
          <span>{{ item.title }}</span>
        </el-menu-item>
      </template>
    </el-menu>
  </el-aside>
</template>

<script setup>
  import {
    Document,
    Menu as IconMenu,
    Setting,
    ArrowDown
  } from '@element-plus/icons-vue'
</script>

<style scoped>
  .el-aside {
    height: 100%;
    overflow: hidden;
    background-color: rgb(238, 241, 246);
  }
  .avatar {
    display: flex;
    justify-content: flex-start;
    padding: 15px 20px;
  }
  .el-menu-vertical:not(.el-menu--collapse) {
    min-height: 100%;
  }
  .el-menu-item {
    --el-menu-item-height: 44px;
    --el-menu-item-font-size: 16px;
  }
</style>
