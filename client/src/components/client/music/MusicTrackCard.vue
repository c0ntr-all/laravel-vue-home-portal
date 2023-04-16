<template>
  <div
    class="music-track row self-start items-center col-grow q-pr-sm rounded-borders"
    :class="{'music-track--active': track.id === musicPlayer.track.id}"
  >
    <div class="flex items-center col-grow" @click="$emit('play')">
      <div class="music-track__image q-mr-md">
        <template v-if="track.id === musicPlayer.track.id">
          <q-spinner-audio
            v-if="musicPlayer.status === 'playing'"
            size="1rem"
            color="white"
          />
          <div class="text-white" v-else>....</div>
        </template>
        <q-icon
          class="music-track__status-icon"
          size="xs"
          :name="musicPlayer.status === 'paused' || (musicPlayer.status === 'playing' && musicPlayer.track.id !== track.id) ? 'play_arrow' : 'pause'"
          flat
          round
          dense
        />
      </div>
      <div class="q-ml-xs">
        <div class="music-track__name">{{ track.name }}</div>
        <div class="music-track__artist">{{ track.artist }}</div>
      </div>
    </div>
    <div class="flex justify-between items-center">
      <div class="music-track__rate q-gutter-y-md">
        <q-rating
          v-model="rate"
          @update:model-value="changeRate"
          :max="4"
          size="1.5em"
          color="primary"
          :icon="[
            'sentiment_very_dissatisfied',
            'sentiment_dissatisfied',
            'sentiment_satisfied',
            'sentiment_very_satisfied'
          ]"
        />
      </div>
      <div class="music-track__time">
        {{ track.duration }}
      </div>
    </div>
  </div>
</template>
<script>
import { computed } from "vue"
import { useMusicPlayer } from "stores/modules/musicPlayer"
import API from "src/utils/api";

export default {
  emits: ['play'],
  props: ['track'],
  setup(props) {
    const musicPlayer = useMusicPlayer()

    let rate = computed({
      get: () => props.track.rate,
      set: value => props.track.rate = value
    })

    const changeRate = async (value) => {
      const previousRate = props.track.rate

      await API.post(`music/tracks/${props.track.id}/rate`, {
        rate: value
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
        rate.value = previousRate
      })
    }

    return {
      rate,
      musicPlayer,
      changeRate
    }
  }
}
</script>
<style lang="scss" scoped>
.music-track {
  position: relative;

  &__image {
    display: flex;
    justify-content: center;
    align-items: center;
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
  &__artist {
    font-size: 12.5px;
    line-height: 16px;
    font-weight: bold;
  }
  &__name {
    font-size: 12.5px;
    line-height: 16px;
  }
  &__rate {
    margin-right: 1rem;
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

    .music-track__image {
      background-color: rgba(0,0,0,.5);
    }
  }
  &:hover {
    .music-track__status-icon {
      display: flex;
    }
  }
}
</style>
