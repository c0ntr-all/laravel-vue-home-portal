<template>
  <el-header><h2 class="page-header">Напоминания</h2></el-header>
  <el-main>
    <el-button
      type="primary"
      @click="showAddModal"
      circle
    >+</el-button>
  </el-main>
</template>
<script>
  export default {
    methods: {
      showAddModal() {
        alert('test')
      }
    }
  }
</script>

<style scoped>

</style>
