<template>
  <slot name="head">
    <tr v-if="heading" class="app-table__tr">
      <app-table-th v-if="expand">
        <template v-slot:cell>#</template>
      </app-table-th>
      <app-table-th v-for="cell in row" :cell="cell" />
    </tr>
  </slot>
  <slot name="body">
    <tr v-if="!heading" class="app-table__tr">
      <app-table-td v-if="expand">
        <template v-slot:cell>
          <q-btn @click="expanded = !expanded" :icon="expanded ? 'expand_more' : 'chevron_right'" size="xs" round dense />
        </template>
      </app-table-td>
      <app-table-td v-for="cell in row" :cell="cell" />
    </tr>
  </slot>
</template>
<script>
  import { ref } from 'vue'

  import AppTableTd from 'components/extra/table/AppTableTd.vue'
  import AppTableTh from 'components/extra/table/AppTableTh.vue'

  export default {
    props: {
      heading: Boolean,
      row: Object,
      expand: Boolean
    },
    components: { AppTableTd, AppTableTh },
    setup(props) {
      return {
        expanded: ref('false')
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
