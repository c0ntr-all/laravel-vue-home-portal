<template>
  <q-btn
    icon="arrow_back"
    color="primary"
    :to="url"
  >
    <div class="q-ml-xs">{{ text }}</div>
  </q-btn>
</template>

<script setup>
const props = defineProps(['album'])
const url = props.album.parent_id ? `/music/albums/${props.album.parent_id}/show` : `/music/artists/${props.album.artist.id}/albums`
const text = props.album.parent_id ? 'Вернуться к основному альбому' : 'Вернуться к исполнителю'
</script>

<style scoped lang="scss">

</style>
