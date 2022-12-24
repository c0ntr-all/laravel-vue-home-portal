<template>
  <div class="skeleton__album-card">
    <el-skeleton-item variant="image" class="skeleton__album-image" />
    <div style="padding: 14px">
      <el-skeleton-item variant="h3" class="skeleton__album-title" />
      <div class="skeleton__album-info">
        <el-skeleton-item variant="text" class="skeleton__album-text-1" />
        <el-skeleton-item variant="text" class="skeleton__album-text-2" />
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>
  .skeleton {
    &__album-card {
      width: 200px;
    }
    &__album-info {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 16px;
      height: 16px;
    }
    &__album-image {
      width: 200px;
      height: 200px;
    }
    &__album-title {
      width: 50%;
    }
    &__album-text-1 {
      margin-right: 16px;
    }
    &__album-text-2 {
      width: 30%
    }
  }
</style>
