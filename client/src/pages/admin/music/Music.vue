<template>
  <q-page class="q-pa-lg">
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
                <div class="text-h6 q-mb-md">Редактирование исполнителей</div>
                <artists-edit></artists-edit>
              </q-tab-panel>

              <q-tab-panel name="upload">
                <div class="text-h6 q-mb-md">Загрузить исполнителей</div>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis praesentium cumque magnam odio iure quidem, quod illum numquam possimus obcaecati commodi minima assumenda consectetur culpa fuga nulla ullam. In, libero.</p>
              </q-tab-panel>
            </q-tab-panels>
          </template>

        </q-splitter>
      </q-tab-panel>

      <q-tab-panel name="tags">
        <div class="text-h6">Теги</div>
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
      </q-tab-panel>
    </q-tab-panels>
  </q-page>
</template>
<script>
import {ref} from 'vue'
import ArtistsEdit from 'src/components/admin/music/artists/ArtistsEdit.vue'

export default {
  components: {ArtistsEdit},

  setup() {
    const mainTabs = ref('artists')
    const artistsTabs = ref('edit')
    const artistsSplitter = ref()

    return {
      mainTabs,
      artistsTabs,
      artistsSplitter
    }
  }
}
</script>
