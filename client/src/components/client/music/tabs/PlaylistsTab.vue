<template>
  <p class="text-h6 q-pa-md">Total: {{ total }}</p>
  <q-card class="q-mb-md" flat bordered>
    <q-card-section>
      <div class="row">
        <PlaylistCard v-for="playlist in items" :playlist="playlist" class="col-4" />
      </div>
    </q-card-section>
  </q-card>
</template>
<script>
import {ref,onMounted} from 'vue'

import API from 'src/utils/api'

import PlaylistCard from 'components/client/music/playlists/PlaylistCard.vue'

export default {
  components: {PlaylistCard},
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
