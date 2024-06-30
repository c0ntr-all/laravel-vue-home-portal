<template>
  <div class="text-h5 q-mb-lg">Другие альбомы исполнителя</div>
  <div class="row items-start q-gutter-md">
    <album-card
      v-for="album in albums"
      :key="album.id"
      :album="album"
      :class="{'album-card--current': album.id === albumId}"
    />
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import AlbumCard from "components/client/music/AlbumCard.vue";
import { api } from "src/boot/axios";

const props = defineProps({
  artistId: Number,
  albumId: Number
})
const loading = ref(true)
const albums = ref([])

const getAlbums = async (artistId) => {
  await api.get(`music/artists/${artistId}/albums`)
    .then(response => {
      const {data: {data}} = response
      albums.value = data.items
    }).catch(error => {
      $q.notify({
        type: 'negative',
        message: `Server Error: ${error.response.data.message}`
      })
    }).finally(() => {
      loading.value = false
    })
}

onMounted(function () {
  getAlbums(props.artistId)
})
</script>
<style lang="scss" scoped>
.album-card--current {
  opacity: .2;
}
</style>
