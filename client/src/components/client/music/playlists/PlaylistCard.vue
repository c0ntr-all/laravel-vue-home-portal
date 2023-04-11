<template>
  <q-card class="playlist-card">
    <q-img src="images/no-image.jpg" :alt="playlist.name">
      <div class="absolute-bottom text-h6">
        <router-link :to="'/music/playlists/' + playlist.id" class="playlist-card__link">{{ playlist.name }}</router-link>
      </div>
    </q-img>
  </q-card>
</template>
<script>
import { ref } from 'vue'

export default {
  props: {
    playlist: Object
  },
  setup() {
    return {
      loading: ref(true)
    }
  }
}
</script>
<style lang="scss" scoped>
.playlist-card {
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
