<template>
  <div class="text-h4 q-mb-md">Tracks</div>
<!--  I've tried to make a skeleton component with name TracksTabSkeleton for it, but it fired and error:
      Internal server error: Cannot read property 'url' of undefined
      Interesting, that it works with name like ArtistsTabSkeleton or another.
      It seems that the word "Tracks" is unacceptable for this component.
-->
  <tracks-filter @submitFilter="getTracks" />
  <template v-if="loading">
    <q-markup-table style="max-width: 960px;">
      <thead>
        <tr>
          <th class="text-left">
            <q-skeleton type="text" width="15px" />
          </th>
          <th class="text-right">
          </th>
          <th class="text-right">
            <q-skeleton type="text" width="65px" />
          </th>
          <th class="text-right">
            <q-skeleton type="text" width="65px" />
          </th>
          <th class="text-right">
            <q-skeleton type="text" width="65px" />
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="n in 30" :key="n">
          <td class="text-left">
            <q-skeleton type="text" width="15px" />
          </td>
          <td class="text-right">
            <q-skeleton type="text" width="100px" />
          </td>
          <td class="text-right">
            <q-skeleton type="text" width="200px" />
          </td>
          <td class="text-left">
            <q-skeleton type="text" width="200px" />
          </td>
          <td class="text-right">
            <q-skeleton type="text" width="100px" />
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </template>
  <template v-else>
    <q-table
      :rows="tracks"
      :columns="columns"
      row-key="name"
      :flat="true"
      :rows-per-page-options="[0]"
      :pagination.sync="{page: 1, rowsPerPage: 0}"
      class="tracks"
    >
      <template v-slot:body="props" :key="props.row.id">
        <track-card-row :props="props" @play="initPlay" />
      </template>
    </q-table>
  </template>
</template>
<script>
import { onMounted, ref } from "vue";
import { useQuasar } from "quasar";

import { useMusicPlayer } from "stores/modules/musicPlayer";
import API from "src/utils/api";

import TracksFilter from "src/components/client/music/TracksFilter.vue"
import TrackCardRow from "src/components/client/music/TrackCardRow.vue"

export default {
  components: { TracksFilter, TrackCardRow },
  setup() {
    const columns = ref([{
      name: "number",
      required: true,
      label: '#',
      align: 'center',
      field: row => row.number,
      sortable: true,
      style: 'width: 70px'
    },{
      name: "rate",
      required: true,
      label: '',
      align: 'center',
      field: row => row.rate,
      sortable: true,
      style: 'width: 120px'
    },{
      name: "name",
      required: true,
      label: 'Имя',
      align: 'left',
      field: row => row.name,
      sortable: true
    },{
      name: "artist",
      required: true,
      label: 'Исполнитель',
      align: 'left',
      field: row => row.artist,
      sortable: true
    },{
      name: "duration",
      required: true,
      label: 'Длительность',
      align: 'right',
      field: row => row.duration,
      sortable: true,
      style: 'width: 130px'
    }])
    const tracks = ref([])
    const loading = ref(true)
    let pagination = ref({
      perPage: 0,
      hasPages: false,
      nextPageUrl: '',
      prevPageUrl: ''
    })
    let paginationLoading = ref(false)

    const $q = useQuasar()
    const musicPlayer = useMusicPlayer()

    const getTracks = async filters => {
      filters = filters || {}

      await API.post('music/tracks/get', filters).then(response => {
          tracks.value = response.data.tracks
          pagination.value = response.data.pagination

          loading.value = false
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }

    const loadMoreTracks = async () => {
      if (pagination.value.hasPages) {
        paginationLoading.value = true
        let obUrl = new URL(pagination.value.nextPageUrl)
        let cursor = obUrl.searchParams.get("cursor")

        await API.post('music/tracks/get', {'cursor': cursor})
          .then(response => {
            pagination.value = response.data.pagination
            tracks.value.push(...response.data.tracks)
            paginationLoading.value = false
          })
      }
    }

    onMounted(() => {
      getTracks()
    })

    return {
      columns,
      tracks,
      musicPlayer,
      loading,
      getTracks,
      loadMoreTracks,
      initPlay: track => {
        // todo: Transfer all this constructions to one repository
        // Replacing playlist with new track
        if (!musicPlayer.playlist.includes(track)) {
          musicPlayer.setPlaylist(tracks.value)
        }
        musicPlayer.playTrack(track)
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.tracks {
  max-width: 960px;
}
</style>
