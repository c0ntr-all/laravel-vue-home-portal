<template>
  <el-main>
    <h1 class="page-header">Управление музыкой</h1>
    <div class="page-content">
      <el-tabs type="border-card">
        <el-tab-pane label="Исполнители">
          <music-upload-artist></music-upload-artist>
        </el-tab-pane>
        <el-tab-pane label="Альбомы">
          <music-upload-album></music-upload-album>
        </el-tab-pane>
        <el-tab-pane label="Теги">
          <music-tag-manager></music-tag-manager>
        </el-tab-pane>
      </el-tabs>
    </div>
  </el-main>
</template>
<script>
  import MusicUploadArtist from "../../components/admin/music/artists/MusicUploadArtist";
  import MusicUploadAlbum from "../../components/admin/music/albums/MusicUploadAlbum";
  import MusicTagManager from "../../components/admin/music/tags/MusicTagManager";

  export default {
    components: {
      MusicUploadArtist,
      MusicUploadAlbum,
      MusicTagManager
    }
  }
</script>

<style lang="scss">
</style>
