<template>
  <AlbumsTabSkeleton v-if="loading" />
  <q-card class="q-mb-md" flat v-else>
    <q-card-section>
      <div class="artist-albums q-mb-lg" v-if="albums">
        <div class="text-h5 q-mb-sm">Альбомы</div>
        <div class="row items-start q-gutter-md">
          <album-card v-for="album in albums" :key="album.id" :album="album"></album-card>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup>
import AlbumCard from "components/client/music/AlbumCard.vue"
import { api } from "boot/axios"
import { onMounted, ref } from "vue"
import AlbumsTabSkeleton from "components/client/music/artist/tabs/AlbumsTabSkeleton.vue"

const loading = ref(true)
const albums = ref({})
const props = defineProps({
  artistId: String,
})

const getAlbums = async id => {
  await api.post(`music/artists/${id}`)
    .then(response => {
      const {data: {data}} = response
      albums.value = data.albums
    }).catch(error => {

    }).finally(() => {
      loading.value = false
    })
}
onMounted(() => {
  getAlbums(props.artistId)
})
</script>

<style scoped>

</style>
