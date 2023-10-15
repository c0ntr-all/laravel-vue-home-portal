<template>
  <q-tabs
    v-model="tabs"
    dense
    class="text-grey"
    active-color="primary"
    indicator-color="primary"
    align="left"
    narrow-indicator
  >
    <q-tab name="tracks" label="Треки" />
    <q-tab name="artists" label="Исполнители" />
    <q-tab name="tags" label="Теги" />
  </q-tabs>

  <q-separator />

  <q-tab-panels v-model="tabs">
    <q-tab-panel name="tracks">
      <TracksTab />
    </q-tab-panel>
    <q-tab-panel name="artists">
      <ArtistsTab />
    </q-tab-panel>
    <q-tab-panel name="tags">
      <TagsTab />
    </q-tab-panel>
  </q-tab-panels>
</template>
<script>
import { ref } from "vue"

import ArtistsTab from "components/admin/music/tabs/artists/ArtistsTab.vue"
import TagsTab from "components/admin/music/tabs/tags/TagsTab.vue"
import TracksTab from "src/components/admin/music/tabs/tracks/TracksTab.vue"

export default {
  components: {ArtistsTab, TagsTab, TracksTab},

  setup() {
    const tabs = ref('artists')

    return {
      tabs
    }
  }
}
</script>
