<template>
  <div class="text-h5 q-mb-md">Исполнители</div>
  <q-splitter v-model="artistsSplitter">
    <template v-slot:before>
      <q-tabs
        v-model="tabs"
        vertical
        class="text-teal"
      >
        <q-tab name="edit" icon="edit_square" label="Edit" />
        <q-tab name="upload" icon="upload" label="Upload" />
      </q-tabs>
    </template>

    <template v-slot:after>
      <q-tab-panels
        v-model="tabs"
        animated
        transition-prev="slide-down"
        transition-next="slide-up"
      >
        <q-tab-panel name="edit">
          <artists-edit />
        </q-tab-panel>

        <q-tab-panel name="upload">
          <artists-upload />
        </q-tab-panel>
      </q-tab-panels>
    </template>
  </q-splitter>
</template>

<script>
import { ref } from "vue"

import ArtistsEdit from "components/admin/music/tabs/artists/ArtistsEdit.vue"
import ArtistsUpload from "components/admin/music/tabs/artists/ArtistsUpload.vue"

export default {
  name: "ArtistsTab.vue",
  components: { ArtistsEdit, ArtistsUpload },
  setup() {
    const artistsSplitter = ref(6)
    const tabs = ref('edit')

    return {
      artistsSplitter,
      tabs
    }
  }
}
</script>

<style scoped>

</style>
