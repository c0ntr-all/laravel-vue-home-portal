<template>
  <table class="app-table">
    <thead>
      <app-table-tr :row="columns" heading />
    </thead>
    <tbody>
      <app-table-tr v-for="row in preparedRows" :row="row" />
    </tbody>
  </table>
</template>
<script>
import {computed, onMounted} from "vue"
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

    columns: Array
  },
  setup(props) {
    const preparedHeading = computed(() => {
      return [props.columns.map(col => col.label)]
    })
    const preparedRows = computed(() => {
      return props.rows.map(row => {
        return props.columns.map(col => {
          return col.field(row)
        })
      })
    })

    return {
      preparedHeading,
      preparedRows
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
