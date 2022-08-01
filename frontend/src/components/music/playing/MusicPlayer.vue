<template>
  <div class="music-player__open-button" @click="playerOpen = true"></div>
  <el-drawer
    v-model="playerOpen"
    title="Music Player"
    :direction="'rtl'"
  >
    <div class="music-player">
      <div class="music-player__album-image">
        <img src="/storage/no-image.gif" alt="" data-v-4ee1772b="">
      </div>
      <div class="music-player__line">
        <div class="music-player__button-nav">
          <icon-base icon-name="shuffle" view-box="0 0 22 22"><icon-music-shuffle /></icon-base>
        </div>
        <div class="music-player__button-nav">
          <icon-base icon-name="prev-track"><icon-music-prev /></icon-base>
        </div>
        <div class="music-player__button-play">
          <icon-base icon-name="play"><icon-play /></icon-base>
        </div>
        <div class="music-player__button-nav">
          <icon-base icon-name="next-track"><icon-music-next /></icon-base>
        </div>
        <div class="music-player__button-nav">
          <icon-base icon-name="repeat" view-box="-2 -3 24 24"><icon-music-repeat /></icon-base>
        </div>
      </div>
    </div>
  </el-drawer>
</template>
<script>
  import IconBase from "../../default/icons/IconBase"
  import IconMusicShuffle from "../../default/icons/IconMusicShuffle"
  import IconMusicPrev from "../../default/icons/IconMusicPrev"
  import IconPlay from "../../default/icons/IconPlay"
  import IconMusicNext from "../../default/icons/IconMusicNext"
  import IconMusicRepeat from "../../default/icons/IconMusicRepeat"

  export default {
    data() {
      return {
        playerOpen: false
      }
    },
    methods: {
    },
    components: {IconBase, IconMusicShuffle, IconMusicPrev, IconPlay, IconMusicNext, IconMusicRepeat}
  }
</script>
<style lang="scss">
  .music-player {
    &__album-image {
      display: flex;
      justify-content: center;
      margin-bottom: 1rem;

      img {
        width: 100%;
        max-width: 300px;
      }
    }
    &__line {
      display: flex;
      justify-content: center;
      align-items: center;
      column-gap: 1rem;
    }
    &__button-nav {
      svg {
        width: 30px;
        height: 30px;
      }
    }
    &__button-play {
      svg {
        width: 50px;
        height: 50px;
      }
    }
    &__open-button {
      position: absolute;
      right: 0;
      top: 0;
      width: 10px;
      height: 100%;
      background: gray;
    }
  }
</style>
