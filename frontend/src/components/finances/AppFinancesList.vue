<template>
  <div v-if="data.length !== 0">
    <el-space direction="vertical">
      <el-card v-for="item in data"  class="box-card" shadow="hover">
        <div class="box-card__image">

        </div>
        <div class="box-card__content">
          <p class="box-card__date">{{ item.doneAt }}</p>
          <p class="box-card__comment">{{ item.comment }}</p>
          <p class="box-card__summ"><b>{{ item.summ }}</b></p>
        </div>
      </el-card>
    </el-space>
  </div>
  <div v-else class="card error">
    <h4>Данных нет</h4>
    <button @click="$emit('load')">Загрузить</button>
  </div>
</template>

<script>
  export default {
    emits: ['load'],
    props: ['data']
  }
</script>

<style lang="scss" scoped>
  .box-card {
    &__content {
      p:not(:last-child) {
        margin-bottom: 10px;
      }
    }
    &__date {
      line-height: 12px;
      font-size: 80%;
      color: #909399;
    }
  }
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
    width: 480px;
  }
</style>
