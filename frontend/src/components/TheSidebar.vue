<template>
  <el-aside class="sidebar">
    <div class="sidebar__header">Unreal Portal</div>
    <el-menu class="app-side-menu el-menu-vertical" :router="true" :default-active="$route.path">
      <template v-for="item in $router.options.routes">
        <el-menu-item :index="item.path" v-if="item.menu">
          <el-icon><icon-menu /></el-icon>
          <span>{{ item.meta.title }}</span>
        </el-menu-item>
      </template>
      <el-divider />
      <template v-for="item in $router.options.routes">
        <el-menu-item :index="item.path" v-if="item.admin">
          <el-icon><icon-menu /></el-icon>
          <span>{{ item.meta.title }}</span>
        </el-menu-item>
      </template>
    </el-menu>
  </el-aside>
</template>

<script>
  export default {
  }
</script>
<script setup>
  import {
    Document,
    Menu as IconMenu,
    Setting,
  } from '@element-plus/icons-vue'
</script>

<style lang="scss" scoped>
  .sidebar {
    width: 220px;

    &__header {
      display: flex;
      align-items: center;
      height: 56px;
      padding: 0 20px;
      background: #374f65;
      color: #fff;
    }
  }
  .el-aside {
    height: 100%;
    overflow: hidden;
    background-color: #253544;
  }
  .app-side-menu {
    border: none;
    background: none;

    .el-menu-item {
      color: #b4bcc8;
      font-family: 'Open Sans',sans-serif;
      &:hover {
        color: #fff;
        background-color: #3498db;
      }

      .el-icon {
        justify-content: flex-start;
      }
    }
  }
  .el-menu-vertical:not(.el-menu--collapse) {
    min-height: 100%;
  }
</style>
