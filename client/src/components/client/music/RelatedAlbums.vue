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
<script>
import {ref} from 'vue'
import AlbumCard from "components/client/music/AlbumCard.vue";
import API from "src/utils/api";

export default {
  props: {
    artistId: Number,
    albumId: Number
  },
  components: {
    AlbumCard
  },
  setup() {
    const loading = ref(true)
    const albums = ref([])
    const getArtist = async (artistId) => {
      const {data} = await API.post('music/artists', {id: artistId})

      albums.value = data.artists?.albums
    }
    return {
      loading,
      albums,
      getArtist
    }
  },
  mounted() {
    this.getArtist(this.artistId)
  }
}
</script>
<style lang="scss" scoped>
.album-card--current {
  opacity: .2;
}
</style>
