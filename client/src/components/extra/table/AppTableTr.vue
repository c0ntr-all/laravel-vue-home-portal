<template>
  <tr v-if="heading" class="app-table__tr">
    <app-table-th v-if="expand">#</app-table-th>
    <app-table-th v-for="cell in columns" :cell="cell" />
  </tr>
  <tr v-if="!heading" class="app-table__tr">
    <app-table-td v-if="expand">
      <q-btn
        v-if="row.children"
        @click="expanded = !expanded"
        :icon="expanded ? 'expand_more' : 'chevron_right'"
        size="xs"
        round
        dense
      />
      <span v-else></span>
    </app-table-td>
    <app-table-td v-for="cell in preparedRow" :cell="cell" />
  </tr>
</template>
<script>
import {computed, ref} from 'vue'

  import AppTableTd from 'components/extra/table/AppTableTd.vue'
  import AppTableTh from 'components/extra/table/AppTableTh.vue'

  export default {
    props: {
      heading: Boolean,
      row: Object,
      columns: Array,
      expand: Boolean
    },
    components: { AppTableTd, AppTableTh },
    setup(props) {
      let preparedRow = []
      if (!props.heading) {
        preparedRow = computed(() => {
          return props.columns.map(col => {
            return col.field(props.row)
          })
        })
      }

      return {
        preparedRow,
        expanded: ref('false')
      }
    },
  }
</script>
<style lang="scss" scoped>
.app-table {
  &__tr {
  }
}
</style>
