<template>
  <q-btn label="Open Player" color="primary" @click="dialog = true" />
  <q-dialog v-model="dialog" position="right" style="height: 100%">
    <q-card class="music-player">
      <q-card-section class="row items-center no-wrap">
        <q-img
          :src="musicPlayer.track.image"
          spinner-color="white"
          style="width:320px; height: 320px"
        />
      </q-card-section>

      <q-card-section class="row justify-center no-wrap q-pa-sm">
        <span>{{ musicPlayer.track.artist }} - {{ musicPlayer.track.name }}</span>
      </q-card-section>

      <q-card-section class="row justify-center no-wrap q-pa-sm">
        <q-btn @click="musicPlayer.shuffle()" icon="shuffle" flat round />
        <q-btn
          @click="musicPlayer.prevTrack()"
          icon="skip_previous"
          flat
          round
        />
        <q-btn
          @click="musicPlayer.run()"
          :icon="musicPlayer.status === 'playing' ? 'pause' : 'play_arrow'"
          flat
          round
        />
        <q-btn
          @click="musicPlayer.nextTrack()"
          icon="skip_next"
          flat
          round
        />
        <q-btn icon="repeat" flat round />
      </q-card-section>

      <q-card-section class="music-player__rewind row items-center no-wrap q-px-md q-pb-sm">
        <span class="q-pr-xs">{{ musicPlayer.timePassed }}</span>
        <q-slider
          class="music-player__rewind-progress"
          @change="rewindNavigate"
          v-model="musicPlayer.rewindProgressWidth"
          :min="0"
          :max="100"
        />
        <span class="q-pl-xs">{{ musicPlayer.timeTotal }}</span>
      </q-card-section>

      <q-card-section class="music-player__volume row justify-center items-center no-wrap q-px-md q-pt-sm">
        <q-icon
          size="xs"
          name="volume_up"
        />
        <q-slider
          class="music-player__volume-progress"
          @change="volumeNavigate"
          v-model="musicPlayer.volume"
          :min="0.00"
          :max="1.00"
          :step="0.01"
          label
        />
      </q-card-section>
      <q-card-section>
        <div class="playlist">
          <q-table
            title="Плейлист"
            :rows="musicPlayer.playlist"
            :columns="columns"
            row-key="name"
            :flat="true"
            :rows-per-page-options="[0]"
            :pagination.sync="{page: 1, rowsPerPage: 0}"
          >
            <template v-slot:body="props">
              <q-tr
                class="table-track"
                :class="{'table-track--active': props.row.id === musicPlayer.track.id}"
                :props="props"
                @click="initPlay(props.row)"
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
                      class="table-track__play-icon"
                      icon="play_arrow"
                      flat
                      round
                      dense
                      v-if="musicPlayer.status === 'paused' || (musicPlayer.status === 'playing' && musicPlayer.track.id !== props.row.id)"
                    />
                    <q-btn
                      class="table-track__play-icon"
                      icon="pause"
                      flat
                      round
                      dense
                      v-else
                    />
                    <div class="table-track__number">{{ col.value }}</div>
                  </template>
                  <template v-else>
                    {{ col.value }}
                  </template>
                </q-td>
              </q-tr>
            </template>
          </q-table>
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script>
import { ref } from 'vue'
import { useMusicPlayer } from 'src/stores/modules/musicPlayer'

import TrackCard from 'src/components/default/PlaylistTrackCard.vue'

export default {
  components: { TrackCard },
  setup() {
    const musicPlayer = useMusicPlayer()
    const columns = ref([{
      name: "number",
      required: true,
      label: '#',
      align: 'center',
      field: row => row.number,
      sortable: true,
      style: 'width: 70px'
    },{
      name: "name",
      required: true,
      label: 'Имя',
      align: 'left',
      field: row => row.name,
      sortable: true
    },{
      name: "duration",
      required: true,
      label: 'Длительность',
      align: 'right',
      field: row => row.duration,
      sortable: true,
      style: 'width: 130px'
    }])

    return {
      dialog: ref(false),
      hovered: ref(false),
      columns,
      musicPlayer,
      initPlay: track => {
        musicPlayer.playTrack(track)
      },
      rewindNavigate: value => {
        musicPlayer.audio.currentTime = (value / 100) * musicPlayer.audio.duration;
      },
      volumeNavigate: value => {
        musicPlayer.audio.volume = value;
      },
    }
  },
  created() {
    this.musicPlayer.init()
  },
}
</script>
<style lang="scss" scoped>
.music-player {
  width: 352px;
  height: 100%;
  max-height: 100%;

  &__volume {
    width: 150px;
  }
}
.table-track {
  &:hover {
    cursor: pointer;

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