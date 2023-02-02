<template>
  <tr>
    <app-table-td v-for="cell in cells" :cell="cell" />
  </tr>
</template>
<script>
  import { computed,  } from "vue"
  import AppTableTd from 'components/extra/table/AppTableTd.vue'

  export default {
    props: {
      row: Object,
      cols: Array
    },
    components: { AppTableTd },
    setup(props) {
      return {
        cells: computed(() => {
          return props.cols.map(col => {
            return col.field(props.row)
          })
        })
      }
    }
  }
</script>
<style>

</style>
