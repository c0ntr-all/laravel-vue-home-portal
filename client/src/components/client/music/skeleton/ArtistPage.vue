<template>
  <div class="q-mb-sm">
    <q-skeleton type="QBtn" width="200px" />
  </div>

  <div class="artist-head q-mb-lg">
    <div class="artist-head__left">
      <div class="artist-head__image">
        <q-skeleton height="200px" width="200px" square />
      </div>
    </div>
    <div class="artist-head__right">
      <h2 class="artist-head__name">
        <q-skeleton type="text" width="300px" />
      </h2>
      <div class="artist-head__description">
        <p>
          <q-skeleton type="text" />
          <q-skeleton type="text" />
          <q-skeleton type="text" />
        </p>
      </div>
      <div class="artist-head__tags">
        <div class="flex q-gutter-sm q-mb-xs">
          <q-skeleton type="QChip" />
          <q-skeleton type="QChip" />
          <q-skeleton type="QChip" />
          <q-skeleton type="QChip" />
        </div>
        <div class="flex q-gutter-sm">
          <q-skeleton type="QChip" />
          <q-skeleton type="QChip" />
        </div>
      </div>
    </div>
  </div>

  <div class="artist-albums q-mb-lg">
    <div class="text-h5 q-mb-sm">
      <q-skeleton type="text" width="200px" />
    </div>
    <div class="row items-start q-gutter-md">
      <album-card-skeleton v-for="item in [1,2,3,4,5]" />
    </div>
  </div>
</template>
<script>
import AlbumCardSkeleton from 'src/components/client/music/skeleton/AlbumCard.vue'

export default {
  components: { AlbumCardSkeleton }
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
