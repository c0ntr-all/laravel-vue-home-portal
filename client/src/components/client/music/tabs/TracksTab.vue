<template>
  <div class="text-h4 q-mb-md">Tracks</div>
  <q-table
    title="Треки"
    :rows="tracks"
    :columns="columns"
    row-key="name"
    :flat="true"
    :rows-per-page-options="[0]"
    :pagination.sync="{page: 1, rowsPerPage: 0}"
  >
    <template v-slot:body="props">
      <track-card-row :props="props" @play="initPlay" />
    </template>
  </q-table>
</template>
<script>
import {onMounted, ref} from "vue";
import { useQuasar } from "quasar";

import { useMusicPlayer } from "stores/modules/musicPlayer";
import API from "src/utils/api";

import TrackCardRow from 'src/components/client/music/TrackCardRow.vue'

export default {
  components: { TrackCardRow },
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

    const getTracks = async () => {
      await API.post('music/tracks/get').then(response => {
          tracks.value = response.data.tracks
          pagination.value = data.pagination

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
      getTracks,
      loadMoreTracks,
      initPlay: track => {
        // todo: Transfer all this constructions to one repository
        // Replacing playlist with new track
        if (!musicPlayer.playlist.includes(track)) {
          musicPlayer.setPlaylist(tracks)
        }
        musicPlayer.playTrack(track)
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
