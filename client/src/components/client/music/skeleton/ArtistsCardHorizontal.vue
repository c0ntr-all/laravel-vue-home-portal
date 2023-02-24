<template>
  <q-card class="artist-card" flat bordered>
    <q-card-section horizontal>
      <q-skeleton class="artist-card__image" square />
      <q-card-section class="flex column justify-between">
        <div class="text-h6 q-mb-sm">
          <q-skeleton type="text" />
        </div>
        <div class="flex q-gutter-sm q-mb-xs">
          <q-skeleton type="QChip" />
          <q-skeleton type="QChip" />
          <q-skeleton type="QChip" />
          <q-skeleton type="QChip" />
        </div>
      </q-card-section>
    </q-card-section>
  </q-card>
</template>
<script>
export default {

}
</script>
<style lang="scss" scoped>
.artist-card {
  width: 100%;
  height: 150px;

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
