<template>
  <q-card class="artist-card">
    <q-img :src="artist.image" :alt="artist.name + ' image'">
      <div class="absolute-bottom text-h6">
        <router-link :to="`/music/artists/${artist.id}/albums`" class="artist-card__link">{{ artist.name }}</router-link>
      </div>
    </q-img>
    <q-card-section class="q-pa-sm">
      <q-chip v-for="tag in artist.tagsNames.common" size="sm" color="primary" text-color="white" outline>
        {{ tag }}
      </q-chip>
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
  width: 100%;
  max-width: 250px;

  &__link {
    text-decoration: none;
    color: #fff;

    &:hover {
      color: #ccc;
    }
  }
}
</style>
