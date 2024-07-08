<template>
  <q-card class="q-mb-md" flat>
    <q-card-section>
      <div class="artist-albums q-mb-lg">
        <div class="text-h5 q-mb-sm">
          <q-skeleton type="text" width="200px"/>
        </div>
        <div class="row items-start q-gutter-md">
          <album-card-skeleton v-for="item in [1,2,3,4,5]"/>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup>
import AlbumCardSkeleton from 'components/client/music/skeleton/AlbumCard.vue'

</script>

<style scoped>

</style>
