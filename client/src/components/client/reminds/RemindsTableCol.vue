<template>
  <template v-if="col.name === 'active'">
    <q-toggle
      v-model="row.isActive"
      @click="switchActive(row)"
      checked-icon="add"
      unchecked-icon="remove"
    />
  </template>
  <template v-else-if="col.name === 'time_left'">
    {{ col.value.message }}
  </template>
  <template v-else>
    {{ col.value }}
  </template>
</template>
<script>
import {useQuasar} from "quasar"

import API from 'src/utils/api'

export default {
  props: ['col', 'row'],
  emits: ['activeSwitched'],
  setup(props, {emit}) {
    const $q = useQuasar()

    const switchActive = async row => {
      await API.post(`reminds/${row.id}/update`, {
        'id': row.id,
        'is_active': row.isActive,
      }).then(response => {
        emit('activeSwitched')
        $q.notify({
          type: 'positive',
          message: `The status of remind has been changed!`
        })
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }

    return {
      switchActive
    }
  }
}
</script>
<style>

</style>
