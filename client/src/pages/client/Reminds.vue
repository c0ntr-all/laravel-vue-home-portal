<template>
  <q-page class="q-pa-lg">
    <q-table
      :rows="reminds"
      :columns="columns"
      row-key="title"
      :flat="true"
      :rows-per-page-options="[0]"
      :pagination.sync="{page: 1, rowsPerPage: 0}"
      dense
    >

      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th auto-width />
          <q-th
            v-for="col in props.cols"
            :key="col.name"
            :props="props"
          >
            {{ col.label }}
          </q-th>
        </q-tr>
      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td auto-width>
            <q-btn size="sm" round dense @click="props.expand = !props.expand" :icon="props.expand ? 'expand_more' : 'chevron_right'" />
          </q-td>
          <q-td
            v-for="col in props.cols"
            :key="col.name"
            :props="props"
          >
            <template v-if="col.name === 'active'">
              <q-toggle v-model="col.value" checked-icon="add" unchecked-icon="remove" />
            </template>
            <template v-else>
              {{ col.value }}
            </template>
          </q-td>
        </q-tr>
        <q-tr v-show="props.expand" :props="props">
          <q-td colspan="100%">
            <div class="text-left">This is expand slot for row above: {{ props.row.title }}.</div>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </q-page>
</template>
<script>
import {ref, onMounted} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

export default {
  setup() {
    const $q = useQuasar()

    const columns = [
      { name: 'title', label: 'Title', field: 'title', align: 'left', sortable: true },
      { name: 'date', label: 'Date', field: 'datetime', align: 'center', sortable: true },
      { name: 'active', label: 'Active', field: 'isActive', align: 'center', }
    ]
    const reminds = ref([])

    const getReminds = async () => {
      await API.get('reminds').then(response => {
        reminds.value = response.data.reminds
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }

    onMounted(() => {
      getReminds()
    })

    return {
      columns,
      reminds
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
