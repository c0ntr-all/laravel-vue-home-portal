<template>
  <h3>Управление исполнителями</h3>
  <el-tabs tab-position="top">
    <el-tab-pane label="Добавить">
      <music-artist-add></music-artist-add>
    </el-tab-pane>
    <el-tab-pane label="Добавить с сервера">
      <music-artist-upload-from-server></music-artist-upload-from-server>
    </el-tab-pane>
    <el-tab-pane label="Редактирование">
      <music-artist-edit></music-artist-edit>
    </el-tab-pane>
  </el-tabs>
</template>
<script>
  import MusicArtistAdd from "./MusicArtistAdd"
  import MusicArtistEdit from "./MusicArtistEdit"
  import MusicArtistUploadFromServer from "./MusicArtistUploadFromServer"

  export default {
    data() {
      return {
      }
    },
    components: {
      MusicArtistAdd,
      MusicArtistUploadFromServer,
      MusicArtistEdit,
    },
    mounted() {
    }
  }
</script>

<style lang="scss">
</style>
