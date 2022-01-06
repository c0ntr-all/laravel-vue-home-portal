<template>
  <el-row class="tac">
    <el-col :span="24">
      <el-menu default-active="2" class="el-menu-vertical">
        <el-menu-item index="1">
          <el-icon><icon-menu /></el-icon>
          <span>Главная</span>
        </el-menu-item>
        <el-menu-item index="2">
          <el-icon><document /></el-icon>
          <span>Финансы</span>
        </el-menu-item>
        <el-menu-item index="3">
          <el-icon><setting /></el-icon>
          <span>Задачи</span>
        </el-menu-item>
      </el-menu>
    </el-col>
  </el-row>
</template>

<script setup>
  import {
    Document,
    Menu as IconMenu,
    Setting,
  } from '@element-plus/icons-vue'
</script>

<style scoped>
  .el-menu-vertical:not(.el-menu--collapse) {
    width: 200px;
    min-height: 100%;
  }
  .el-menu-item {
    --el-menu-item-height: 44px;
    --el-menu-item-font-size: 16px;
  }
</style>
