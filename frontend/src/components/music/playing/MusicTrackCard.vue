<template>
  <div class="track-card" @click="play(track)">
    <div class="track-card__play-icon" v-if="status === 'pause' || (status === 'play' && player.track.id !== track.id)">
      <icon-base icon-name="play"><icon-play /></icon-base>
    </div>
    <div class="track-card__play-icon" v-else>
      <icon-base icon-name="stop"><icon-stop /></icon-base>
    </div>
    <div class="track-card__number">{{ track.number }}</div>
    <div class="track-card__name">{{ track.name }}</div>
    <div class="track-card__duration">{{ track.duration }}</div>
  </div>
</template>
<script>
  import {mapGetters, mapActions} from 'vuex'

  import IconBase from "../../default/icons/IconBase";
  import IconPlay from "../../default/icons/IconPlay";
  import IconStop from "../../default/icons/IconStop";

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
    components: {IconBase, IconPlay, IconStop}
  }
</script>
<style lang="scss" scoped>
  .track-card {
    display: flex;
    position: relative;
    padding: .75rem;
    border-bottom: 1px solid #d7d7d7;

    &__play-icon {
      display: none;
      position: absolute;
      top: 0.325rem;
      left: 0.325rem;

      svg {
        width: 30px;
        height: 30px;
      }
    }

    &:hover {
      cursor: pointer;
      background: rgba(0, 0, 0, .1);

      .track-card__play-icon {
        display: inline-block;
      }
      .track-card__number {
        color: #fff;
      }
    }

    &__number {
      flex: 0 0 40px;
      color: #777;
    }
    &__name {
      flex: 1 1 100%;
    }
    &__duration {
      color: #777;
    }
  }
</style>
