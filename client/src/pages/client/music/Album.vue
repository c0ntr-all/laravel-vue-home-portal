<template>
  <AlbumPageSkeleton v-if="loading" />
  <template v-else>
    <div class="q-mb-sm">
      <BackButton :album="album" />
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
            <div class="tags-list q-gutter-sm">
              <q-chip v-for="tag in album.tags" color="primary" text-color="white">{{ tag.name }}</q-chip>
            </div>
          </div>
        </div>
      </div>

      <div class="album-body q-mb-lg bg-white">
        <MusicTracksList :tracks="album.tracks" />
      </div>
    </div>

    <div v-if="album.versions.count" class="album-versions q-mb-lg">
      <div class="album-versions__title text-h5 q-mb-lg">Другие версии альбома ({{ album.versions.count }})</div>
      <q-table
        :card-container-style="'background-color: #ff0000'"
        :rows="album.versions.items"
        :columns="[
          { name: 'id', field: 'id'},
          { name: 'image', field: 'image', align: 'left', style: 'width: 50px' },
          { name: 'name', field: 'name', align: 'left', sortable: true },
          { name: 'date', field: 'date', align: 'left', sortable: true, format: (val, row) => formatDate(val) },
        ]"
        :visible-columns="['image', 'name', 'date']"
        row-key="name"
        hide-header
        hide-bottom
        flat
      >
        <template v-slot:body="props">
<!--          todo: Сделать нормальную ссылку с ховером-->
          <q-tr
            class="album-versions__row"
            :props="props"
            :key="`m_${props.row.index}`"
            @click="$router.push(`/music/albums/${props.row.id}/show`)"
          >
            <q-td
              v-for="col in props.cols"
              :key="col.name"
              :props="props"
            >
              <template v-if="col.name === 'image'">
                <q-img
                  :width="'50px'"
                  :src="col.value"
                />
              </template>
              <template v-else>
                {{ col.value }}
              </template>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </div>

    <div class="related-albums">
      <div>
        <!-- v-if="loading === false" чтобы компонент дождался загрузки основного альбома -->
        <RelatedAlbums v-if="loading === false" :artistId="album.artist.id" :albumId="parseInt(id)" />
      </div>
    </div>
  </template>
</template>
<script setup>
import { ref, onMounted, watch } from "vue"
import { useRoute, useRouter } from "vue-router"

import { api } from "src/boot/axios"
import { useMusicPlayer } from "stores/modules/musicPlayer"

import AlbumPageSkeleton from "src/components/client/music/skeleton/AlbumPage.vue"
import RelatedAlbums from "components/client/music/RelatedAlbums.vue"
import MusicTracksList from "src/components/client/music/MusicTracksList.vue"
import BackButton from "components/client/music/album/BackButton.vue"

const props = defineProps({
'id': String
})
const $router = useRouter()
const route = useRoute()

const loading = ref(true)
const showImage = ref(false)
const album = ref({})
const musicPlayer = useMusicPlayer()

const getAlbum = async id => {
  await api.get(`music/albums/${id}`)
  .then(response => {
    album.value = response.data.data
  }).catch(error => {
    if(error.response.status === 404) {
      $router.push('/404')
    }
  }).finally(() => {
    loading.value = false
  })
}

const addToPlaylist = () => {
  musicPlayer.addToPlaylist(album.value.tracks)
}

const formatDate = dateString => {
  return new Date(dateString).getFullYear();
};

onMounted(() => {
  getAlbum(props.id)
})

watch(() => route.params, (toParams, previousParams) => {
    getAlbum(toParams.id)
  }
)
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
.album-tracks {
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
}
.album-versions {
  max-width: 720px;
  border-right: 1px solid #ccc;

  &__row {
    &:hover {
      cursor: pointer;
    }
  }
}
.album-year {
  color: #777;
}
</style>
