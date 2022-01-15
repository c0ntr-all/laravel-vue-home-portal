<template>
  <el-card class="box-card">
    <template #header>
      <div class="card-header">
        <span>Card name</span>
        <el-button class="button" type="text">Operation button</el-button>
      </div>
    </template>
    <div class="text item" v-for="item in items">
      {{ item }}
    </div>
    <el-form @submit.prevent="addItem">
      <el-input placeholder="Введите заголовок!" v-model="title" />
    </el-form>
  </el-card>
</template>

<script>
  export default {
    data() {
      return {
        items: [],
        title: ''
      }
    },
    methods: {
      addItem() {
        this.items.push(this.title)
        this.title = ''
      },
    }
  }
</script>

<style scoped>
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .text {
    font-size: 14px;
  }

  .item {
    margin-bottom: 18px;
  }

  .box-card {
  }
</style>
