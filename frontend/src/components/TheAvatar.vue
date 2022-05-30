<template>
  <div class="avatar">
    <el-popover placement="bottom" :width="200" trigger="click" v-model:visible="profileMenuVisible">
      <template #reference>
        <div class="avatar__inner">
          <div class="avatar__block">
            <el-avatar :size="32" :src="circleUrl"></el-avatar>
          </div>
          <div class="avatar__block">
            <el-icon><arrow-down /></el-icon>
          </div>
        </div>
      </template>
      <div class="popover-content avatar-menu">
        <el-menu class="el-menu-vertical" :router="true">
          <el-menu-item :index="'/profile'" @click="profileMenuVisible = false">
            <el-icon><user /></el-icon>
            <span>Профиль</span>
          </el-menu-item>
          <el-menu-item :index="'/musicupload'" @click="profileMenuVisible = false">
            <el-icon><Upload /></el-icon>
            <span>Загрузка музыки</span>
          </el-menu-item>
          <el-menu-item :index="'2'" @click="[hideProfileMenu(), logout()]">
            <el-icon><d-arrow-left /></el-icon>
            <span>Выйти</span>
          </el-menu-item>
        </el-menu>
      </div>
    </el-popover>
  </div>
</template>

<script setup>
  import {
    Menu as IconMenu,
    ArrowDown,
    DArrowLeft,
    User,
    Upload
  } from '@element-plus/icons-vue'
</script>
<script>
  export default {
    inject: ['logout'],
    data() {
      return {
        circleUrl: 'https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png',
        profileMenuVisible: false
      }
    },
    methods: {
      hideProfileMenu() {
        this.profileMenuVisible = false
      }
    }
  }
</script>

<style lang="scss" scoped>
  .avatar {
    display: flex;
    justify-content: flex-start;
    padding: 0 15px;

    &__inner {
      display: flex;
      align-items: center;
      padding: 10px;
      cursor: pointer;

      &:hover {
        background-color: #d3ecfb;
      }

      .el-icon {
        margin: 0 8px 0 6px;
      }
    }
    &__block {
      display: flex;
    }
    &-menu {
      .el-menu {
        border: none;
      }
    }
  }
</style>
