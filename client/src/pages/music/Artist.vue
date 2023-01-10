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
          {{ artist.content }}
        </div>
        <div class="artist-head__tags">
          <div class="tags-list">
<!--            <q-btn v-for="tag in artist.tagsNames.common" class="mx-1" />-->
<!--            <q-btn v-for="tag in artist.tagsNames.secondary" class="mx-1" />-->
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>
<script>
import {ref} from 'vue'
import API from "src/utils/api";

export default {
  setup() {
    const loading = ref(true)
    const artist = ref({})
    const getArtist = async (id) => {
      const {data} = await API.post('music/artists', {id: id})

      artist.value = data.artists
    }

    return {
      loading,
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
</style>
