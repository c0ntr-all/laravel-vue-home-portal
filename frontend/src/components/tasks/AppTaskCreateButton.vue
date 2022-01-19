<template>
  <el-card class="box-card" v-if="show">
    <template #header>
      <div class="card-header">
        <el-form>
          <el-input placeholder="Введите заголовок!" />
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
    props: {
      show: Boolean
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
