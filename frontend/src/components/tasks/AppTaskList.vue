<template>
  <el-card class="box-card">
    <template #header>
      <div class="card-header">
        <span>{{ listTitle }}</span>
        <el-button class="button" type="text">Operation button</el-button>
      </div>
    </template>
    <div class="text task" v-for="item in items">
      <a href="#" class="task__link" @click.prevent="openTask">{{ item.title }}</a>
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
      openTask() {
        alert('open task')
      }
    }
  }
</script>

<style lang="scss" scoped>
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .text {
    font-size: 14px;
  }

  .task {
    margin-bottom: 18px;

    &__link {
      display: block;
      padding: 8px;
      text-decoration: none;
      color: #000;
      border-radius: 3px;
      box-shadow: 0 1px 0 #091e4240;

      &:hover {
        background-color: #f4f5f7;
        border-bottom-color: #091e4240;
      }
    }
  }

  .box-card {
  }
</style>
