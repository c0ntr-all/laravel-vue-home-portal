<template>
  <table class="app-table">
    <thead>
      <app-table-tr :columns="columns" :expand="expand" heading />
    </thead>
    <tbody>
      <app-table-tr v-for="row in rows" :row="row" :columns="columns" :expand="expand" />
    </tbody>
  </table>
</template>
<script>
import AppTableTr from 'components/extra/table/AppTableTr.vue'

export default {
  components: { AppTableTr },
  props: {
    rows: {
      type: Array,
      default: () => []
    },
    rowKey: {
      type: String,
      default: 'id'
    },
    columns: Array,
    expand: Boolean
  },
  setup(props) {
    return {
    }
  },
}
</script>
<style>
.app-table {
  width: 100%;
  max-width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}
</style>
