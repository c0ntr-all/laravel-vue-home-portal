<template>
  <p class="text-h6 q-pa-md">Total: {{ total }}</p>
  <q-btn
    class="q-mb-lg"
    @click="createPlaylistModal = true"
    icon="add"
    label="Create playlist"
    color="primary"
    size="md"
    dense
  />
  <q-dialog v-model="createPlaylistModal" @show="">
    <q-card class="playlist-modal">
      <q-card-section class="flex justify-between">
        <div class="text-h6">Create playlist</div>
        <q-btn @click="createPlaylistModal = false" icon="close" size="md" flat rounded dense />
      </q-card-section>

      <q-separator />

      <q-card-section>
        <q-input
          v-model="newPlaylistName"
          type="text"
          label="Playlist name"
          filled
          dense
        />
      </q-card-section>

      <q-separator />

      <q-card-section class="flex justify-end">
        <q-btn class="q-px-sm q-mr-md" @click="createPlaylistModal = false" dense flat>Cancel</q-btn>
        <q-btn @click="createPlaylist" class="q-px-md" color="primary" dense>Create</q-btn>
      </q-card-section>
    </q-card>
  </q-dialog>
  <q-card class="q-mb-md" flat bordered>
    <q-card-section>
      <div class="row q-gutter-md">
        <PlaylistCard v-for="playlist in items" :playlist="playlist" />
      </div>
    </q-card-section>
  </q-card>
</template>
<script setup>
import { ref, onMounted } from "vue"
import { useQuasar } from "quasar"
import { api } from "boot/axios"

import PlaylistCard from "components/client/music/playlists/PlaylistCard.vue"

const $q = useQuasar()

const createPlaylistModal = ref(false)
const newPlaylistName = ref('')
const items = ref([])
const total = ref(0)

const getItems = async () => {
  await api.post('music/playlists').then(response => {
    items.value = response.data.items
    total.value = response.data.total
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: error.response.data.message
    })
  })
}

const createPlaylist = async () => {
  await api.put('music/playlists/store', {
    name: newPlaylistName.value
  }).then(response => {
    items.value.push(response.data.data)
    total.value++
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  })
}

onMounted(() => {
  getItems()
})
</script>
<style lang="scss" scoped>

</style>
