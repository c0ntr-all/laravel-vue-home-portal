<template>
  <el-header><h1 class="page-header">Загрузка музыки</h1></el-header>
  <el-main>
    <el-tabs type="border-card">
      <el-tab-pane label="Загрузка банды">
        <music-upload-artist></music-upload-artist>
      </el-tab-pane>
      <el-tab-pane label="Загрузка альбома">
        <music-upload-album></music-upload-album>
      </el-tab-pane>
    </el-tabs>
  </el-main>
</template>
<script>
  import MusicUploadArtist from "../../components/music/MusicUploadArtist";
  import MusicUploadAlbum from "../../components/music/MusicUploadAlbum";

  export default {
    components: {
      MusicUploadArtist,
      MusicUploadAlbum
    }
  }
</script>

<style lang="scss">
</style>
