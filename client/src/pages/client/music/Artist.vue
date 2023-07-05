<template>
  <ArtistPageSkeleton v-if="loading" />
  <template v-else>
    <div class="q-mb-sm">
      <q-btn
        icon="arrow_back"
        color="primary"
        :to="'/music'"
      ><div class="q-ml-xs">Вернуться назад</div></q-btn>
    </div>

    <div class="artist-head q-mb-lg">
      <div class="artist-head__left">
        <div class="artist-head__image">
          <a :href="artist.image"><img :src="artist.image" alt=""></a>
        </div>
      </div>
      <div class="artist-head__right">
        <h2 class="artist-head__name">{{ artist.name }}</h2>
        <div class="artist-head__description">
          <p v-if="artist.content">{{ artist.content }}</p>
          <p v-else class="text-grey-5">Описание отсутствует</p>
        </div>
        <div class="artist-head__tags">
          <div class="tags-list q-gutter-sm">
            <q-chip v-for="tag in artist.tagsNames?.common" color="primary" text-color="white">{{ tag }}</q-chip>
          </div>
          <div class="tags-list q-gutter-sm">
            <q-chip v-for="tag in artist.tagsNames?.secondary">{{ tag }}</q-chip>
          </div>
        </div>
      </div>
    </div>

    <div class="artist-albums q-mb-lg" v-if="this.artist.albums">
      <div class="text-h5 q-mb-sm">Альбомы</div>
      <div class="row items-start q-gutter-md">
        <album-card v-for="album in this.artist.albums" :key="album.id" :album="album"></album-card>
      </div>
    </div>
  </template>
</template>
<script>
import { ref, onMounted } from "vue"
import { useRouter } from "vue-router"

import { api } from "src/boot/axios"

import ArtistPageSkeleton from "src/components/client/music/skeleton/ArtistPage.vue"
import AlbumCard from "components/client/music/AlbumCard.vue"

export default {
  props: {
    'id': String
  },
  components: { AlbumCard, ArtistPageSkeleton },
  setup(props) {
    const $router = useRouter()

    const artist = ref({})
    const loading = ref(true)

    const getArtist = async id => {
      await api.post(`music/artists/${id}/show`)
        .then(response => {
          artist.value = response.data.data
          loading.value = false
        }).catch(error => {
          if(error.response.status === 404) {
            $router.push('/404')
          }
        })
    }

    onMounted(() => {
      getArtist(props.id)
    })

    return {
      artist,
      loading,
      getArtist
    }
  },
}
</script>

<style lang="scss" scoped>
.artist-head {
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
  }
}
</style>
