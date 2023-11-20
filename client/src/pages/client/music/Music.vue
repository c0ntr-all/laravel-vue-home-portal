<template>
  <q-card class="q-mb-md" flat>
    <q-tabs
      v-model="tab"
      align="left"
      no-caps
      outside-arrows
      mobile-arrows
    >
      <q-tab name="tracks" label="Tracks" />
      <q-tab name="artists" label="Artists" />
      <q-tab name="playlists" label="Playlists" />
      <q-tab name="genres" label="Genres" />
      <q-tab name="history" label="History" />
    </q-tabs>
  </q-card>
  <q-tab-panels
    v-model="tab"
    animated
    swipeable
    vertical
    transition-prev="jump-up"
    transition-next="jump-up"
  >
    <q-tab-panel name="tracks" class="q-pa-none">
      <TracksTab />
    </q-tab-panel>

    <q-tab-panel name="artists" class="q-pa-none">
      <ArtistsTab />
    </q-tab-panel>

    <q-tab-panel name="playlists" class="q-pa-none">
      <PlaylistsTab />
    </q-tab-panel>

    <q-tab-panel name="genres" class="q-pa-none">
      <TagsTab />
    </q-tab-panel>

    <q-tab-panel name="history" class="q-pa-none">
      <HistoryTab />
    </q-tab-panel>
  </q-tab-panels>
</template>
<script setup>
import { ref } from "vue"

import TracksTab from "components/client/music/music/tabs/tracks/TracksTab.vue"
import ArtistsTab from "components/client/music/music/tabs/artists/ArtistsTab.vue"
import PlaylistsTab from "src/components/client/music/music/tabs/PlaylistsTab.vue"
import TagsTab from "src/components/client/music/music/tabs/tags/TagsTab.vue"
import HistoryTab from "src/components/client/music/music/tabs/HistoryTab.vue"

const tab = ref('artists')
</script>

<style lang="scss" scoped>
  .tags-list {
    position: relative;
  }
  .q-tab-panels {
    background-color: transparent;
  }
</style>
