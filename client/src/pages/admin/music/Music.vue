<template>
  <q-tabs
    v-model="mainTabs"
    dense
    class="text-grey"
    active-color="primary"
    indicator-color="primary"
    align="left"
    narrow-indicator
  >
    <q-tab name="artists" label="Исполнители" />
    <q-tab name="tags" label="Теги" />
  </q-tabs>

  <q-separator />

  <q-tab-panels v-model="mainTabs">
    <q-tab-panel name="artists">
      <div class="text-h5 q-mb-md">Исполнители</div>
      <q-splitter
        v-model="artistsSplitter"
      >
        <template v-slot:before>
          <q-tabs
            v-model="artistsTabs"
            vertical
            class="text-teal"
          >
            <q-tab name="edit" icon="edit_square" label="Edit" />
            <q-tab name="upload" icon="upload" label="Upload" />
          </q-tabs>
        </template>

        <template v-slot:after>
          <q-tab-panels
            v-model="artistsTabs"
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
    </q-tab-panel>

    <q-tab-panel name="tags">
      <tags-edit />
    </q-tab-panel>
  </q-tab-panels>
</template>
<script>
import {ref} from 'vue'
import ArtistsEdit from 'src/components/admin/music/artists/ArtistsEdit.vue'
import ArtistsUpload from 'src/components/admin/music/artists/ArtistsUpload.vue'
import TagsEdit from 'src/components/admin/music/tags/TagsEdit.vue'

export default {
  components: {ArtistsEdit, ArtistsUpload, TagsEdit},

  setup() {
    const mainTabs = ref('artists')
    const artistsTabs = ref('edit')
    const artistsSplitter = ref(6)

    return {
      mainTabs,
      artistsTabs,
      artistsSplitter
    }
  }
}
</script>
