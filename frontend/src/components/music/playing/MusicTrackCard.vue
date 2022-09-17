<template>
  <div class="track-card" @click="play(track)" :class="{active: track.id === player.track.id}">
    <div class="track-card__start-column">
      <div class="track-card__play-icon" v-if="status === 'pause' || (status === 'play' && player.track.id !== track.id)">
        <icon-base icon-name="play"><icon-music-play /></icon-base>
      </div>
      <div class="track-card__play-icon" v-else>
        <icon-base icon-name="stop"><icon-music-pause /></icon-base>
      </div>
      <div class="track-card__number">{{ track.number }}</div>
    </div>
    <div class="track-card__name">{{ track.name }}</div>
    <div class="track-card__rate">
      <el-rate
        v-model="track.rate"
        :texts="['bad', 'lyric', 'normal', 'good', 'hit']"
        show-text
      />
    </div>
    <div class="track-card__duration">{{ track.duration }}</div>
  </div>
</template>
<script>
  import {mapGetters, mapActions} from 'vuex'

  import IconBase from "../../default/icons/IconBase";
  import IconMusicPlay from "../../default/icons/IconMusicPlay";
  import IconMusicPause from "../../default/icons/IconMusicPause";

  export default {
    props: {
      track: Object
    },
    methods: {
      ...mapActions(['play']),
    },
    computed: {
      ...mapGetters(['player','status'])
    },
    components: {IconBase, IconMusicPlay, IconMusicPause}
  }
</script>
<style lang="scss" scoped>
  .track-card {
    display: flex;
    align-items: center;
    position: relative;
    height: auto;
    min-height: 45px;
    border-bottom: 1px solid #d7d7d7;

    &__play-icon {
      display: none;
      position: absolute;
      top: 7.5px;
      left: 5px;

      svg {
        width: 30px;
        height: 30px;
      }
    }

    &:hover {
      cursor: pointer;
      background: rgba(0, 0, 0, .1);

      .track-card__play-icon {
        display: flex;
      }
      .track-card__number {
        display: none;
      }
    }
    &.active {
      background: rgba(0, 0, 0, .1);

      .track-card__play-icon {
        display: flex;
      }
      .track-card__number {
        display: none;
      }
    }

    &__start-column {
      flex: 0 0 40px;
      height: 45px;
    }
    &__number {
      position: absolute;
      top: 12.5px;
      left: 15px;
      color: #777;
    }
    &__name {
      flex: 1 1 100%;
    }
    &__rate {
      flex: 1 0 200px;
    }
    &__duration {
      color: #777;
    }
  }
</style>
