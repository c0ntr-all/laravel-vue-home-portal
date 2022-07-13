<template>
  <el-row :gutter="12">
    <el-col :span="24">
      <el-card shadow="hover"> {{ track.number }} {{ track.name }} </el-card>
    </el-col>
  </el-row>
</template>
<script>
  export default {
    props: {
      track: Object
    }
  }
</script>
<style lang="scss" scoped>

</style>
