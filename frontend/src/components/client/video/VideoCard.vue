<template>
  <el-row>
    <el-col>
      <el-card class="video-card" shadow="always" @click.prevent="$emit('openModal', this.item)">
        {{ item.path }}
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
export default {
  props: {
    item: Object
  },
  emits: ['openModal'],
}

</script>

<style lang="scss" scoped>
.video-card {
  &:hover {
    cursor: pointer;
  }
}
</style>
