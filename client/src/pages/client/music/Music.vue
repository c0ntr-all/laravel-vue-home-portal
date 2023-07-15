<template>
  <q-tabs
    v-model="tab"
    align="left"
    class="q-mb-md"
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
      <q-card>
        <q-card-section>
          <div class="text-h4 q-mb-md">Genres</div>
          <div class="tags q-mb-lg">
            <div class="tags-list q-gutter-sm">
              <q-btn
                v-for="tag in tags.common"
                :key="tag.id"
                :label="tag.label"
                :to="'/music/tags/' + tag.slug"
                color="primary"
                size="sm"
                rounded
                unelevated
              />
              <q-inner-loading :showing="tagsLoading">
                <q-spinner-gears size="50px" color="primary" />
              </q-inner-loading>
            </div>
          </div>
        </q-card-section>
      </q-card>
    </q-tab-panel>

    <q-tab-panel name="history" class="q-pa-none">
      <HistoryTab />
    </q-tab-panel>
  </q-tab-panels>
</template>
<script>
import TracksTab from 'src/components/client/music/tabs/TracksTab.vue'
import ArtistsTab from 'src/components/client/music/tabs/ArtistsTab.vue'
import PlaylistsTab from 'src/components/client/music/tabs/PlaylistsTab.vue'
import HistoryTab from 'src/components/client/music/tabs/HistoryTab.vue'

import { ref, onMounted } from "vue";
import API from "src/utils/api";

export default {
  components: { TracksTab, ArtistsTab, PlaylistsTab, HistoryTab },
  setup() {
    const tags = ref([])
    let tagsLoading = ref(true)

    const getTags = async () => {
      await API.post('music/tags/tree').then(response => {
        tags.value = response.data.tags
        tagsLoading.value = false
      })
    }

    onMounted(() => {
      getTags()
    })

    return {
      tab: ref('artists'),
      tags,
      tagsLoading,
      getTags
    }
  }
}
</script>

<style lang="scss" scoped>
  .tags-list {
    position: relative;
  }
  .q-tab-panels {
    background-color: transparent;
  }
</style>
