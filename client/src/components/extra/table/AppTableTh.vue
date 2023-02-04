<template>
  <th class="app-table__th text-left">{{ cell.label }}</th>
</template>
<script>
export default {
  props: {
    cell: Object
  },
  setup(props) {
    return {

    }
  }
}
</script>
<style lang="scss" scoped>
.app-table {
  &__th {
    padding: 4px 8px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
    font-weight: 500;
    font-size: 12px;
  }
}
</style>
