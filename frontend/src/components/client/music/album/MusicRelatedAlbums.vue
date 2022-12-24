<template>
  <div class="related-albums">
    <h3>Другие альбомы исполнителя</h3>
    <el-skeleton :loading="loading" animated>
      <template #template>
        <div class="skeleton__albums-wrap">
          <music-skeleton-album-card v-for="item in [1, 2, 3]" />
        </div>
      </template>
      <template #default>
        {{ albumId }}
      </template>
    </el-skeleton>
  </div>
</template>
<script>
import MusicSkeletonAlbumCard from './MusicSkeletonAlbumCard'

export default {
  props: {
    albumId: Number
  },
  data() {
    return {
      loading: true
    }
  },
  components: {
    MusicSkeletonAlbumCard
  }
}
</script>
<style lang="scss" scoped>
  .skeleton {
    &__albums-wrap {
      display: flex;
      justify-content: flex-start;
      column-gap: 1rem;
    }
  }
</style>
