<template>
  <el-main>
    <h1 class="page-header">Управление музыкой</h1>
    <div class="page-content">
      <el-tabs type="border-card">
        <el-tab-pane label="Исполнители">
          <music-artist-manager></music-artist-manager>
        </el-tab-pane>
        <el-tab-pane label="Альбомы">
          <music-album-manager></music-album-manager>
        </el-tab-pane>
        <el-tab-pane label="Теги">
          <music-tag-manager></music-tag-manager>
        </el-tab-pane>
      </el-tabs>
    </div>
  </el-main>
</template>
<script>
  import MusicArtistManager from "../../components/admin/music/artists/MusicArtistManager";
  import MusicAlbumManager from "../../components/admin/music/albums/MusicAlbumManager";
  import MusicTagManager from "../../components/admin/music/tags/MusicTagManager";

  export default {
    components: {
      MusicArtistManager,
      MusicAlbumManager,
      MusicTagManager
    }
  }
</script>

<style lang="scss">
</style>
