<template>
  <tr v-if="heading"><app-table-td v-for="cell in row" :cell="cell" /></tr>
  <tr v-else><app-table-td v-for="cell in row" :cell="cell" /></tr>
</template>
<script>
  import AppTableTd from 'components/extra/table/AppTableTd.vue'

  export default {
    props: {
      heading: Boolean,
      row: Object,
    },
    components: { AppTableTd },
    setup(props) {
      return {

      }
    }
  }
</script>
<style>

</style>
