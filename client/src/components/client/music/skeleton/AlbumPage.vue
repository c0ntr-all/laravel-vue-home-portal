<template>
  <div class="q-mb-sm">
    <q-skeleton type="QBtn" width="200px" />
  </div>
  <div class="album">
    <div class="album-head q-mb-lg">
      <div class="album-head__left">
        <div class="album-head__image q-mb-md">
          <q-skeleton height="200px" width="200px" square />
        </div>
        <div class="album-head__actions">
          <q-skeleton height="32px" width="32px" square />
        </div>
      </div>
      <div class="album-head__right">
        <h2 class="album-head__name">
          <q-skeleton type="text" width="300px" />
        </h2>
        <div class="album-head__description">
          <p class="album-head__description-item">
            <q-skeleton type="text" width="300px" />
          </p>
          <p class="album-head__description-item">
            <q-skeleton type="text" width="50px" />
          </p>
          <div class="album-head__description-item">
            <q-skeleton type="text" />
            <q-skeleton type="text" />
            <q-skeleton type="text" />
          </div>
        </div>
        <div class="album-head__tags">
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
    <div class="album-body">
      <div class="album-tracks">
        <div class="album-tracks__list q-mb-md">
          <q-markup-table>
            <thead>
            <tr>
              <th class="text-left">
                <q-skeleton type="text" width="15px" />
              </th>
              <th class="text-right">
                <q-skeleton type="text" width="24px" />
              </th>
              <th class="text-right">
                <q-skeleton type="text" width="80px" />
              </th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="n in 5" :key="n">
              <td class="text-left">
                <q-skeleton type="text" width="15px" />
              </td>
              <td class="text-right">
                <q-skeleton type="text" width="500px" />
              </td>
              <td class="text-right">
                <q-skeleton type="text" width="65px" />
              </td>
            </tr>
            </tbody>
          </q-markup-table>
        </div>
      </div>
    </div>
  </div>
  <div class="related-albums">
    <div>
      <div class="text-h5 q-mb-lg">
        <q-skeleton type="text" width="200px" />
      </div>
      <div class="row items-start q-gutter-md">
        <album-card-skeleton v-for="item in [1,2,3,4,5]" />
      </div>
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
</style>
