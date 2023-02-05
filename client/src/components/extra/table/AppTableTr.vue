<template>
  <slot name="head">
    <tr v-if="heading" class="app-table__tr">
      <app-table-th v-for="cell in row" :cell="cell" />
    </tr>
  </slot>
  <slot name="body">
    <tr v-if="!heading" class="app-table__tr">
      <app-table-td v-for="cell in row" :cell="cell" />
    </tr>
  </slot>
</template>
<script>
  import AppTableTd from 'components/extra/table/AppTableTd.vue'
  import AppTableTh from 'components/extra/table/AppTableTh.vue'

  export default {
    props: {
      heading: Boolean,
      row: Object,
    },
    components: { AppTableTd, AppTableTh },
    setup(props) {
      return {

      }
    }
  }
</script>
<style lang="scss" scoped>
.app-table {
  &__tr {
  }
}
</style>
