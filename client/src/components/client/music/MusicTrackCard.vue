<template>
  <div
    class="music-track-card row self-start items-center col-grow q-pr-sm rounded-borders"
    :class="{'music-track-card--active': track.id === musicPlayer.track.id}"
  >
    <div class="music-track-card__image flex items-center justify-center q-pa-none">
      <template v-if="track.id === musicPlayer.track.id">
        <q-spinner-audio
          v-if="musicPlayer.status === 'playing'"
          size="1rem"
          color="white"
        />
        <div class="text-white" v-else>....</div>
      </template>
      <q-icon
        class="music-track-card__status-icon"
        size="xs"
        :name="musicPlayer.status === 'paused' || (musicPlayer.status === 'playing' && musicPlayer.track.id !== track.id) ? 'play_arrow' : 'pause'"
        flat
        round
        dense
      />
    </div>
    <div class="q-pa-none q-ml-sm col-grow">
      <div class="flex justify-between items-center">
        <div>
          <div class="music-track-card__track-name">{{ track.name }}</div>
          <div class="music-track-card__artist-name">{{ track.artist }}</div>
        </div>
        <div class="music-track-card__time">
          {{ track.duration }}
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {useMusicPlayer} from "stores/modules/musicPlayer";

export default {
  props: ['track'],
  setup(props) {
    const musicPlayer = useMusicPlayer()

    return {
      musicPlayer
    }
  }
}
</script>
<style lang="scss" scoped>
.music-track-card {
  position: relative;

  &__image {
    width: 40px;
    height: 40px;
    margin: 4px;
    border-radius: 8px;
    background: #ccc;
  }
  &__status-icon {
    display: none;
    position: absolute;
    padding: 4px;
    background: #fff;
    border-radius: 50%;
  }
  &__artist-name {
    font-size: 12.5px;
    line-height: 16px;
    font-weight: bold;
  }
  &__track-name {
    font-size: 12.5px;
    line-height: 16px;
  }
  &__time {
    flex-shrink: 0;
    color: #818c99;
    cursor: pointer;
    font-size: 12px;
    min-width: 3em;
    text-align: right;
  }

  &--active,
  &:hover {
    cursor: pointer;
    background-color: rgba(174,183,194,0.12);

    .music-track-card__image {
      background-color: rgba(0,0,0,.5);
    }
  }
  &:hover {
    .music-track-card__status-icon {
      display: flex;
    }
  }
}
</style>
