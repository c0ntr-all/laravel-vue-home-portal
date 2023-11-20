<template>
  <q-card class="q-mb-md" flat bordered>
    <q-card-section>
      <!--  I've tried to make a skeleton component with name TracksTabSkeleton for it, but it fired and error:
            Internal server error: Cannot read property 'url' of undefined
            Interesting, that it works with name like ArtistsTabSkeleton or another.
            It seems that the word "Tracks" is unacceptable for this component.
      -->
      <TracksFilter @submitFilter="getTracks" />
    </q-card-section>
  </q-card>

  <TracksTabSkeleton v-if="loading" />

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
<script setup>
import { onMounted, ref } from "vue"
import { useQuasar } from "quasar"

import { useMusicPlayer } from "stores/modules/musicPlayer"
import { api } from "boot/axios"

import TracksTabSkeleton from "components/client/music/music/tabs/tracks/TracksTabSkeleton.vue"
import TracksFilter from "components/client/music/music/tabs/tracks/TracksFilter.vue"
import TrackCardRow from "components/client/music/music/tabs/tracks/TrackCardRow.vue"

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

  await api.post('music/tracks', {
    filters: filters,
    with_tags: true
  }).then(response => {
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

    await api.post('music/tracks', {'cursor': cursor})
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
</script>
<style lang="scss" scoped>
.tracks {
}
</style>
