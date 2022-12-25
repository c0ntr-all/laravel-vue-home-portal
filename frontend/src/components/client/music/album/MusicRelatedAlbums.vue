<template>
  <div class="related-albums">
    <h3>Другие альбомы исполнителя</h3>
    <el-skeleton :loading="loading" animated>
      <template #template>
        <div class="skeleton__albums-wrap">
          <music-skeleton-album-card v-for="item in [1, 2, 3]" />
        </div>
      </template>
      <template #default>
        <el-space alignment="flex-start" wrap>
          <music-album-card
            v-for="album in this.albums"
            :key="album.id"
            :album="album"
            :class="{'album-card--current': album.id === this.albumId}"
          />
        </el-space>
      </template>
    </el-skeleton>
  </div>
</template>
<script>
import {mapActions} from "vuex";

import MusicSkeletonAlbumCard from './MusicSkeletonAlbumCard'
import MusicAlbumCard from "@/components/client/music/album/MusicAlbumCard";

export default {
  props: {
    artistId: Number,
    albumId: Number
  },
  data() {
    return {
      loading: true,
      albums: []
    }
  },
  methods: {
    ...mapActions('music', [
      'getArtist'
    ]),

    //todo:Возможно, надо не подгружать всего Исполнителя, а сделать отдельные методы на получение альбомов
    loadArtist() {
      this.getArtist(this.artistId).then(data => {
        this.albums = data.albums
        this.loading = false
      }).catch(error => {
        this.$message.error(error)
        this.loading = false
      })
    },
  },
  components: {
    MusicSkeletonAlbumCard,
    MusicAlbumCard
  },
  mounted() {
    this.loadArtist()
  }
}
</script>
<style lang="scss" scoped>
  .skeleton {
    &__albums-wrap {
      display: flex;
      justify-content: flex-start;
      column-gap: 1rem;
    }
  }
  .album-card--current {
    opacity: .2;
  }
</style>
