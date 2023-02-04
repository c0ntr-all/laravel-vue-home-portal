<template>
  <td class="app-table__td">{{ cell }}</td>
</template>
<script>
export default {
  props: ['cell'],
  setup() {
    return {

    }
  }
}
</script>
<style lang="scss" scoped>
.app-table {
  &__td {
    padding: 4px 8px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
    font-size: 13px;
  }
}
</style>
