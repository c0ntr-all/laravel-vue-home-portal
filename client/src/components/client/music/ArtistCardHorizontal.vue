<template>
  <q-card class="artist-card" flat bordered>
    <q-card-section horizontal>
      <q-img
        class="artist-card__image"
        :src="artist.image"
        :alt="artist.name + ' image'"
      />
      <q-card-section class="flex column justify-between">
        <div class="text-h6 q-mb-sm">
          <router-link :to="`/music/artists/${artist.id}/albums`" class="artist-card__link">{{ artist.name }}</router-link>
        </div>
        <div class="q-gutter-xs">
          <q-chip
            v-for="tag in artist.tagsNames.common"
            size="md"
            color="primary"
            text-color="white"
            outline
          >
            {{ tag }}
          </q-chip>
        </div>
      </q-card-section>
    </q-card-section>
  </q-card>
</template>
<script>
import { ref } from 'vue'

export default {
  props: {
    artist: Object
  },
  setup() {
    return {
      loading: ref(true)
    }
  }
}
</script>
<style lang="scss" scoped>
.artist-card {

  &__image {
    width: 150px;
    height: 150px;
  }

  &__link {
    text-decoration: none;
    color: #000;

    &:hover {
      color: #ccc;
    }
  }
}
</style>
