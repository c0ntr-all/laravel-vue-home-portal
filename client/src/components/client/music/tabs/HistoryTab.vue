<template>
  <template v-if="loading">
    <q-markup-table>
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
import TrackCardRow from "components/client/music/TrackCardRow.vue";
import {onMounted, ref} from "vue";
import API from "src/utils/api";
import {useQuasar} from "quasar";
import {useMusicPlayer} from "stores/modules/musicPlayer";

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
      name: "artist",
      required: true,
      label: 'Исполнитель',
      align: 'left',
      field: row => row.artist,
      sortable: true
    },{
      name: "tags",
      required: true,
      label: 'Теги',
      align: 'left',
      field: row => row.tags,
      sortable: true
    },{
      name: "duration",
      required: true,
      label: 'Длительность',
      align: 'right',
      field: row => row.duration,
      sortable: true,
      style: 'width: 130px'
    },{
      name: "date",
      required: true,
      label: 'Дата прослушивания',
      align: 'right',
      field: row => row.date,
      sortable: true,
      style: 'width: 130px'
    }])
    const tracks = ref([])
    const loading = ref(true)

    const $q = useQuasar()
    const musicPlayer = useMusicPlayer()

    const getTracks = async filters => {
      filters = filters || {}

      await API.post('music/history').then(response => {
        tracks.value = response.data.tracks
        loading.value = false
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
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

</style>
