<template>
  <el-space alignment="flex-start" wrap>
    <app-task v-for="i in 7"></app-task>
  </el-space>
</template>

<script>
  import AppTask from "./AppTask";
  export default {
    data() {
      tasks: []
    },
    components: {AppTask}
  }
</script>

<style lang="scss" scoped>

</style>
