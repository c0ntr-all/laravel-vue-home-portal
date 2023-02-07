<template>
  <tr v-if="heading" class="app-table__tr">
    <app-table-th v-if="expand">#</app-table-th>
    <app-table-th v-for="cell in columns" :cell="cell" />
  </tr>
  <tr v-if="!heading" class="app-table__tr" :class="{'hidden': depth > 0 && !expanded}">
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
    <app-table-td>{{ depth }}</app-table-td>
  </tr>
  <app-table-tr
    v-if="row?.children"
    v-for="row in row.children"
    :row="row"
    :columns="columns"
    :expand="expand"
    :expanded="{depth: depth, expand: expand}"
    :depth="depth + 1"
  />
</template>
<script>
import {computed, ref} from 'vue'

import AppTableTd from 'components/extra/table/AppTableTd.vue'
import AppTableTh from 'components/extra/table/AppTableTh.vue'

export default {
  name: 'app-table-tr',
  props: {
    heading: Boolean,
    row: Object,
    columns: Array,
    expand: Boolean,
    expanded: Object,
    depth: {
      type: Number,
      default: 0
    }
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
