<template>
  <q-tr
    class="table-track"
    :class="{'table-track--active': props.row.id === musicPlayer.track.id}"
    :props="props"
    @mouseover="hovered = true"
    @mouseout="hovered = false"
  >
    <q-td
      v-for="col in props.cols"
      :key="col.name"
      :props="props"
    >
      <template v-if="col.name === 'number'">
        {{ col.id }}
        <q-btn
          @click="play"
          class="table-track__play-icon"
          :icon="
            musicPlayer.status === 'paused' || (musicPlayer.status === 'playing' && musicPlayer.track.id !== props.row.id)
             ? 'play_arrow'
             : 'pause'
          "
          flat
          round
          dense
        />
        <div class="table-track__number">{{ col.value }}</div>
      </template>
      <template v-else-if="col.name === 'rate'">
        <div class="table-track__rate q-gutter-y-md column">
          <q-rating
            v-model="rate"
            @update:model-value="handleRate"
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
      </template>
      <template v-else-if="col.name === 'tags'">
        <div class="table-track__tags">
          <p v-if="col.value?.common.length" class="q-ma-none">{{ col.value.common.join(', ') }}</p>
          <p v-if="col.value?.secondary.length" class="q-ma-none">{{ col.value.secondary.join(', ') }}</p>
        </div>
      </template>
      <template v-else>
        {{ col.value }}
      </template>
    </q-td>
  </q-tr>
</template>
<script>
import { ref, computed } from "vue"
import { useQuasar } from "quasar"

import { useMusicPlayer } from "stores/modules/musicPlayer"
import { api } from "boot/axios"

export default {
  props: ['props'],
  emits: ['play'],
  setup(props, { emit }) {
    const musicPlayer = useMusicPlayer()
    const $q = useQuasar()

    const hovered = ref(false);
    let rate = computed({
      get: () => props.props.row.rate,
      set: value => props.props.row.rate = value
    })

    const play = () => {
      emit('play', props.props.row)
    }

    const handleRate = async (value) => {
      const previousRate = props.props.row.rate

      await api.post(`music/tracks/${props.props.row.id}/rate`, {
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
      hovered,
      rate,
      musicPlayer,
      play,
      handleRate
    }
  }
}
</script>
<style lang="scss" scoped>
.table-track {
  &:hover {
    .table-track__play-icon {
      display: flex;
    }
    .table-track__number {
      display: none;
    }
  }
  &--active {
    background-color: rgba(0, 0, 0, 0.03);
    .table-track__play-icon {
      display: flex;
    }
    .table-track__number {
      display: none;
    }
  }
  &__play-icon {
    display: none;
  }
  &__number {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 2.4em;
    min-width: 2.4em;
  }
}
</style>
