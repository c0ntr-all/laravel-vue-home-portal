<template>
  <el-aside class="sidebar">
    <div class="sidebar__header">Portal</div>
    <el-menu class="el-menu-vertical" :router="true" :default-active="$route.path">
      <template v-for="item in $router.options.routes">
        <el-menu-item :index="item.path" v-if="item.menu">
          <el-icon><icon-menu /></el-icon>
          <span>{{ item.title }}</span>
        </el-menu-item>
      </template>
      <el-divider />
      <template v-for="item in $router.options.routes">
        <el-menu-item :index="item.path" v-if="item.admin">
          <el-icon><icon-menu /></el-icon>
          <span>{{ item.title }}</span>
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
    width: 200px;
    box-shadow: 2px 0 10px rgba(0, 0, 0, .1);

    &__header {
      display: flex;
      align-items: center;
      height: 60px;
      padding: 0 20px;
      background: #262e36;
      color: #fff;
    }
  }
  .el-aside {
    height: 100%;
    overflow: hidden;
    background-color: rgb(238, 241, 246);
  }
  .el-menu-vertical:not(.el-menu--collapse) {
    min-height: 100%;
  }
</style>
