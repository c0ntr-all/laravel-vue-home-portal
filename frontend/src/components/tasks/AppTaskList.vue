<template>
  <el-card class="box-card">
    <template #header>
      <div class="card-header">
        <span>{{ listTitle }}</span>
        <el-button class="button" type="text">Operation button</el-button>
      </div>
    </template>
    <div class="text item" v-for="item in items">
      {{ item.title }}
    </div>
    <el-form @submit.prevent="createItem">
      <el-input placeholder="Введите заголовок!" v-model="newItemTitle" />
    </el-form>
  </el-card>
</template>

<script>
  export default {
    data() {
      return {
        newItemTitle: '',
      }
    },
    props: {
      listTitle: String,
      items: Array,
    },
    methods: {
      async createItem() {
        console.log(this.newListTitle)
        // const response = await fetch('api/tasks/list/store', {
        //   method: 'POST',
        //   headers: {
        //     'Content-Type': 'application/json'
        //   },
        //   body: JSON.stringify({
        //     title: this.newListTitle
        //   })
        // })
        //
        // const data = await response.json()
        // if(data) {
        //   console.log(data.result)
        // }
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
