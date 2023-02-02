<template>
  <table>
    <thead>
<!--      <app-table-row v-for="col in columns" :row="col" />-->
    </thead>
    <tbody>
      <app-table-row v-for="row in rows" :row="row" :cols="columns" />
    </tbody>
  </table>
</template>
<script>
import { onMounted } from "vue"
import AppTableRow from 'components/extra/table/AppTableTr.vue'

export default {
  components: { AppTableRow },
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
    onMounted(() => {
      props.rows.forEach(row => {
        props.columns.forEach(col => {
          console.log(col.field(row))
        })
      })
    })

    return {

    }
  },
}
</script>
