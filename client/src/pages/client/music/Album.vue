<template>
  <AlbumPageSkeleton v-if="loading" />
  <template v-else>
    <div class="q-mb-sm">
      <q-btn type="primary" @click="this.$router.push('/music/artists/' + album.artist.id)">Вернуться к исполнителю</q-btn>
    </div>
    <div class="album">
      <div class="album-head q-mb-lg">
        <div class="album-head__left">
          <div class="album-head__image q-mb-md">
            <a :href="album.image" @click.prevent="showImage = true">
              <img :src="album.image" alt="">
            </a>
            <q-dialog v-model="showImage">
              <img :src="album.image" alt="">
            </q-dialog>
          </div>
          <div class="album-head__actions">
            <q-btn @click="addToPlaylist" icon="playlist_add" dense>
              <q-tooltip anchor="top middle" self="bottom middle" :offset="[5, 5]">
                <span style="font-size: .75rem">Add to playlist</span>
              </q-tooltip>
            </q-btn>
          </div>
        </div>
        <div class="album-head__right">
          <h2 class="album-head__name">{{ album.name }}</h2>
          <div class="album-head__description">
            <p class="album-head__description-item">{{ album.artist?.name }}</p>
            <p class="album-head__description-item">{{ album.year }}</p>
            <div class="album-head__description-item">
              {{ album.content }}
            </div>
          </div>
          <div class="album-head__tags">
            <div class="tags-list">
              <q-btn v-for="tag in album.tags" class="mx-1">{{ tag }}</q-btn>
            </div>
          </div>
        </div>
      </div>
      <div class="album-body">
        <div class="album-tracks" v-if="album.tracks">
          <div class="album-tracks__list">
            <q-table
              title="Треки"
              :rows="album.tracks"
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
          </div>
        </div>
      </div>
    </div>
    <div class="related-albums">
      <div>
        <!-- v-if="loading === false" чтобы компонент дождался загрузки основного альбома -->
        <related-albums v-if="loading === false" :artistId="album.artist.id" :albumId="parseInt(id)" />
      </div>
    </div>
  </template>
</template>
<script>
import { ref, onMounted } from 'vue'
import API from "src/utils/api";

import { useMusicPlayer } from 'stores/modules/musicPlayer'

import AlbumPageSkeleton from 'src/components/client/music/skeleton/AlbumPage.vue'
import AlbumCard from 'components/client/music/AlbumCard.vue'
import RelatedAlbums from "components/client/music/RelatedAlbums.vue"
import TrackCardRow from 'src/components/client/music/TrackCardRow.vue'

export default {
  props: {
    'id': String
  },
  components: { AlbumPageSkeleton, AlbumCard, RelatedAlbums, TrackCardRow },
  setup(props) {
    const loading = ref(true)
    const showImage = ref(false)
    const album = ref({})
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
    const getAlbum = async (id) => {
      const {data} = await API.post('music/albums', {id: id})

      album.value = data.data
      loading.value = false
    }

    const addToPlaylist = () => {
      musicPlayer.addToPlaylist(album.value.tracks)
    }

    const musicPlayer = useMusicPlayer()

    onMounted(() => {
      getAlbum(props.id)
    })

    return {
      loading,
      showImage,
      album,
      columns,
      musicPlayer,
      getAlbum,
      addToPlaylist,
      initPlay: track => {
        // Replacing playlist with new track
        if (!musicPlayer.playlist.includes(track)) {
          musicPlayer.setPlaylist(album.value.tracks)
        }
        musicPlayer.playTrack(track)
      }
    }
  },
  created() {
    // Реагирование на смену id в маршруте альбома
    this.$watch(() => this.$route.params, (toParams, previousParams) => {
        this.getAlbum(toParams.id)
      }
    )
  },
}
</script>

<style lang="scss" scoped>
.album-head {
  display: flex;
  column-gap: 1rem;
  padding: 1rem 0 0 0;

  &__image {
    img {
      width: 200px;
      height: 200px;
    }
  }

  &__name {
    margin: 0 0 1rem 0;
    font-size: 45px;
    line-height: 45px;
    font-weight: 700;
  }

  &__description {
    margin: 0 0 1rem 0;

    &-item {
      margin-bottom: 1rem;
    }
  }
}
.album-body {
  display: flex;
  flex-direction: row;
}
.album-tracks {
  flex: 0 0 760px;

  &__header {
    display: flex;
    align-items: center;
    position: relative;
    height: auto;
    min-height: 45px;
    border-bottom: 1px solid #d7d7d7;

    &-number {
      flex: 0 0 40px;
      text-align: center;
    }
    &-name {
      flex: 1 1 100%;
    }
    &-favorite {
      display: flex;
      flex: 1 0 45px;
      justify-content: center;
      margin-right: 15px;
    }
  }
  &__list {
    display: flex;
    flex-direction: column;
    max-width: 760px;
  }
}

.album-artist {

}
.album-year {
  color: #777;
}
</style>
