<template>
  <el-row :gutter="12">
    <el-col :gutter="8">
      <el-card shadow="always"> {{ path }} </el-card>
    </el-col>
  </el-row>
</template>

<script>
export default {
  props: {
    path: String
  }
}

</script>

<style lang="scss" scoped>

</style>
