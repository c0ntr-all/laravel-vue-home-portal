<template>
  <q-card class="album-card">
    <div class="album-card__image">
      <q-skeleton height="200px" width="200px" square />
    </div>
    <q-card-section class="album-card__info">
      <div class="text-h6">
        <q-skeleton type="text" />
      </div>
      <p class="album-card__year">
        <q-skeleton type="text" />
      </p>
    </q-card-section>
  </q-card>
</template>
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
