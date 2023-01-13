<template>
  <q-page class="q-pa-lg">
    <q-btn type="primary" :to="'/music'">Вернуться назад</q-btn>
    <div class="artist-head">
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
          <div class="tags-list">
<!--            <q-btn v-for="tag in artist.tagsNames.common" class="mx-1" />-->
<!--            <q-btn v-for="tag in artist.tagsNames.secondary" class="mx-1" />-->
          </div>
        </div>
      </div>
    </div>
    <div class="artist-albums" v-if="this.artist.albums">
      <div class="text-h5">Альбомы</div>


      <div class="artists-list q-pa-md row items-start q-gutter-md">
        <q-card v-for="album in this.artist.albums" :key="album.id" :album="album" class="album-card">
          <!--todo Поправить формирование полного урла в будущем (с бека)          -->
          <img :src="'http://home-portal.local/storage/' + album.image">

          <q-card-section class="album-card__info">
            <div class="text-h6">
              <router-link :to="'/music/albums/' + album.id" class="album-card__link">
                <p class="album-card__title" :title="album.name">{{ album.name }}</p>
              </router-link>
            </div>
            <p class="album-card__year">{{ album.year }}</p>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>
<script>
import {ref} from 'vue'
import API from "src/utils/api";

export default {
  setup() {
    const artist = ref({})
    const getArtist = async (id) => {
      const {data} = await API.post('music/artists', {id: id})

      artist.value = data.artists
    }

    return {
      artist,
      getArtist
    }
  },
  props: {
    'id': String
  },
  mounted() {
    this.getArtist(this.id)
  }
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

.album-card {
  max-width: 200px;

  &__image {
    margin-bottom: 5px;

    img, svg {
      width: 200px;
      height: 200px;
    }
  }
  &__info {
    padding: 0 8px;
  }
  &__link {
    color: #222;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;

    &:hover {
      color: $primary;
    }
  }
  &__title {
    margin-bottom: 5px;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  &__year {
    margin-bottom: 5px;
    font-size: 14px;
    color: #777;
  }
}
</style>
