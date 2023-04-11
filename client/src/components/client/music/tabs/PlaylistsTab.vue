<template>
  <p class="text-h6 q-pa-md">Total: {{ total }}</p>
  <q-card class="q-mb-md" flat bordered>
    <q-card-section>
      <div class="row">
        <div v-for="playlist in items" class="col-4">
          {{ playlist.name }}
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>
<script>
import {ref,onMounted} from 'vue'

import API from 'src/utils/api'

export default {
  setup() {
    const items = ref([])
    const total = ref(0)

    const getItems = async () => {
      await API.get('music/playlists').then(response => {
        items.value = response.data.items
        total.value = response.data.total
      }).catch(error => {

      })
    }

    onMounted(() => {
      getItems()
    })

    return {
      total,
      items
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
