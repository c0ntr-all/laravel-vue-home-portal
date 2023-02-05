<template>
  <table class="app-table">
    <thead>
      <app-table-tr :row="preparedHeading" heading />
    </thead>
    <tbody>
      <app-table-tr v-for="row in preparedRows" :row="row" />
    </tbody>
  </table>
</template>
<script>
import { computed, ref } from "vue"
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
    const preparedHeading = ref([...props.columns])
    if (props.expand) {
      preparedHeading.value.unshift({
        name: "#",
        label: '#',
        field: 'test',
      })
    }
    let preparedRows = computed(() => {
      return props.rows.map(row => {
        return preparedHeading.value.map(col => {
          if (typeof col.field === 'function') {
            return col.field(row)
          } else {
            return col.field
          }
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
