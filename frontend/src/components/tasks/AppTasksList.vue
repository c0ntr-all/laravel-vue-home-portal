<template>
  <el-space wrap>
    <app-tasks-item v-for="i in 7"></app-tasks-item>
  </el-space>
</template>

<script>
  import AppTasksItem from "./AppTasksItem";
  export default {
    components: {AppTasksItem}
  }
</script>

<style scoped>

</style>
