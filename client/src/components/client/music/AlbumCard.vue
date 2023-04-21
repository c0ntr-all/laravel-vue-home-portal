<template>
  <q-card class="album-card">
    <!--todo Поправить формирование полного урла в будущем (с бека)          -->
    <div class="album-card__image">
      <img :src="album.image">
    </div>

    <q-card-section class="album-card__info">
      <div class="text-h6">
        <router-link :to="'/music/albums/' + album.id" class="album-card__link">
          <p class="album-card__title" :title="album.name">{{ album.name }}</p>
        </router-link>
      </div>
      <p class="album-card__year">{{ album.year }}</p>
    </q-card-section>
  </q-card>
</template>
<script>
export default {
  props: {
    album: Object
  }
}
</script>
<style lang="scss" scoped>

.album-card {
  width: 100%;
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
