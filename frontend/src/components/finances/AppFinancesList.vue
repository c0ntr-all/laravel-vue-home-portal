<template>
  <div v-if="data.length !== 0">
    <div v-for="item in data" class="card">
      <p>Date: {{ item.date }}</p>
      <p>Description: {{ item.description }}</p>
      <p>Shop: {{ item.shop }}</p>
      <p>Summ: {{ item.summ }}</p>
    </div>
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

<style scoped>

</style>
