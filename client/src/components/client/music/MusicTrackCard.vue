<template>
  <div
    class="music-track flex no-wrap self-start items-center col-grow q-pr-sm rounded-borders"
    :class="{'music-track--active': track.id === musicPlayer.track.id}"
  >
    <div class="music-track__left" @click="$emit('play')">
      <div class="music-track-cover q-mr-md">
        <q-img
          v-if="track.image"
          :src="track.image"
          class="music-track-cover__image"
          :alt="track.name"
        />
        <div class="music-track-cover__overlay">
          <div class="music-track-cover__play-icon">
            <q-spinner-audio
              v-if="musicPlayer.status === 'playing'"
              size="1rem"
              color="white"
            />
            <div class="text-white" v-else>....</div>
          </div>
        </div>
        <q-icon
          class="music-track-cover__status-icon"
          size="xs"
          :name="musicPlayer.status === 'paused' || (musicPlayer.status === 'playing' && musicPlayer.track.id !== track.id) ? 'play_arrow' : 'pause'"
          flat
          round
          dense
        />
      </div>
      <div class="music-track__title">
        <div class="music-track__name">{{ track.name }}</div>
        <div class="music-track__artist">{{ track.artist }}</div>
      </div>
    </div>
    <div class="music-track__right">
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
      <div class="music-track__end-column">
        <div class="music-track__time">
          {{ track.duration }}
        </div>
        <div class="music-track__more">
          <q-btn color="grey-7" icon="more_horiz" round flat>
            <q-menu cover auto-close>
              <q-list>
                <q-item v-for="action in filteredActions" @click="handleFunction(action.name)" clickable>
                  <q-item-section>
                    <div class="flex items-center">
                      <q-icon
                        size="xs"
                        :name="action.icon"
                        flat
                        round
                        dense
                      />
                      <div class="q-ml-xs">{{ action.label }}</div>
                    </div>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </div>
    </div>
  </div>
  <q-dialog v-model="showPlaylistModal" @show="initPlaylistDialog">
    <q-card class="playlist-modal">
      <q-card-section class="flex justify-between">
        <div class="text-h6">Add to playlist</div>
        <q-btn @click="showPlaylistModal = false" icon="close" size="md" flat rounded dense />
      </q-card-section>

      <q-separator />

      <q-card-section>
        <q-input
          v-model="playlistSearch"
          type="search"
          label="Search playlist"
          filled
          dense
        >
          <template v-slot:append>
            <q-btn
              v-if="playlistSearch.length"
              @click="playlistSearch = ''"
              icon="close"
              flat
              rounded
              dense
            />
            <q-icon name="search" />
          </template>
        </q-input>
      </q-card-section>

      <q-separator />

      <q-card-section>
        <div class="playlists-list">
          <q-checkbox
            v-for="item in filteredPlaylists"
            v-model="selectedPlaylists"
            class="playlist-item"
            :label="item.name"
            :val="item.id"
            color="primary"
            left-label
            dense
          />
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section class="flex justify-end">
        <q-btn class="q-px-sm q-mr-md" @click="showPlaylistModal = false" dense flat>Cancel</q-btn>
        <q-btn @click="updatePlaylists" class="q-px-md" color="primary" dense>Save</q-btn>
      </q-card-section>
      <q-inner-loading :showing="playlistsLoading">
        <q-spinner-gears size="50px" color="primary" />
      </q-inner-loading>
    </q-card>
  </q-dialog>
</template>
<script setup>
import { ref, computed, watch } from "vue"
import { useQuasar } from "quasar"
import { useMusicPlayer } from "stores/modules/musicPlayer"
import { api } from "src/boot/axios"

const emit = defineEmits(['play', 'remove'])
const props = defineProps({
  track: {
    type: Object,
    required: true
  },
  actions: {
    type: Array,
    required: false,
    default: ['addToPlaylist']
  },
  playlist: {
    type: Number,
    required: false
  }
})
const $q = useQuasar()
const musicPlayer = useMusicPlayer()

const availableActions = [{
  name: 'addToPlaylist',
  label: 'Add to playlist',
  icon: 'add'
},{
  name: 'deleteFromPlaylist',
  label: 'Remove from playlist',
  icon: 'delete'
}]

const showPlaylistModal = ref(false)
const playlists = ref([])
const playlistsLoading = ref(false)
const filteredPlaylists = ref([])
const playlistSearch = ref('')
const selectedPlaylists = ref([])

let rate = computed({
  get: () => props.track.rate,
  set: value => props.track.rate = value
})

const filteredActions = computed(() => {
  return availableActions.filter(item => props.actions.includes(item.name))
})

const changeRate = async (value) => {
  const previousRate = props.track.rate

  await api.post(`music/tracks/${props.track.id}/rate`, {
    rate: value
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
    rate.value = previousRate
  })
}

const initPlaylistDialog = async () => {
  playlistsLoading.value = true

  await api.post('music/playlists', {with_tracks: true}).then(response => {
    playlists.value = response.data.items
    filteredPlaylists.value = response.data.items

    response.data.items.forEach(playlist => {
      if (playlist.tracks.includes(props.track.id)) {
        selectedPlaylists.value.push(playlist.id)
      }
    })
  }).catch(error => {
    console.log(error)
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  }).finally(() => {
    playlistsLoading.value = false
  })
}

const handleFunction = actionName => {
  switch(actionName) {
    case 'addToPlaylist':
      showPlaylistModal.value = true
      break;

    case 'deleteFromPlaylist':
      deleteFromPlaylist()
      break;
  }
}

const updatePlaylists = async () => {
  await api.patch(`music/tracks/${props.track.id}/playlists/update`, {
    playlists: selectedPlaylists.value
  }).then(response => {
    $q.notify({
      type: 'positive',
      message: 'Playlists updated!'
    })
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  }).finally(() => {
    showPlaylistModal.value = false
  })
}

const deleteFromPlaylist = async () => {
  await api.post(`music/tracks/${props.track.id}/playlists/delete`, {
    playlist: props.playlist
  }).then(response => {
    emit('remove', props.track.id)
    $q.notify({
      type: 'positive',
      message: 'Track has been removed from playlist!'
    })
  }).catch(error => {
    console.log(error)
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  })
}

watch(playlistSearch, (value) => {
  filteredPlaylists.value = playlists.value.filter(item =>
    item.name.toLowerCase().includes(value.toLowerCase())
  )
})
</script>
<style lang="scss" scoped>
.music-track {
  position: relative;

  &__left {
    display: flex;
    align-items: center;
    flex-grow: 1;
    flex-shrink: 1;
    overflow: hidden;
  }

  &__right {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
  }

  &-cover {
    display: flex;
    position: relative;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    margin: 4px;
    border-radius: 8px;
    background: #ccc;

    &__image {
      border-radius: 8px;
    }
    &__status-icon {
      display: none;
      position: absolute;
      padding: 4px;
      background: #fff;
      border-radius: 50%;
    }
    &__play-icon {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    &__overlay {
      display: none;
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 8px;
      background-color: rgba(0,0,0,.5);
    }
  }
  &__artist {
    font-size: 12.5px;
    line-height: 16px;
    font-weight: bold;
  }
  &__title {
    white-space: nowrap;
    overflow: hidden;
    margin-left: 4px;
  }
  &__name {
    font-size: 12.5px;
    line-height: 16px;
    text-overflow: ellipsis;
    overflow: hidden;
  }
  &__rate {
    margin-right: 1rem;
  }
  &__end-column {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 48px;
  }
  &__time {
    flex-shrink: 0;
    color: #818c99;
    cursor: pointer;
    font-size: 12px;
    min-width: 3em;
    text-align: right;
  }
  &__more {
    position: absolute;
    //top: 50%;
    //left: 50%;
    //margin-top: -10px;
    //margin-left: -9px;
    visibility: hidden;
  }

  &--active,
  &:hover {
    cursor: pointer;
    background-color: rgba(174,183,194,0.12);

    .music-track-cover__overlay {
      display: block;
    }
  }
  &:hover {
    .music-track-cover__status-icon {
      display: flex;
    }
    .music-track__more {
      visibility: visible;
    }
    .music-track__time {
      visibility: hidden;
    }
  }
}
.playlist-modal {
  width: 768px;
  max-width: 80vw;
}
.playlists-list {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  height: 60vh;

  .playlist-item {
    display: flex;
    justify-content: space-between;
    padding: 1rem;

    &:not(:last-child) {
      border-bottom: 1px solid rgba(0, 0, 0, 0.12);
    }

    &:hover {
      cursor: pointer;
      background: rgba(0, 0, 0, 0.05);
    }
  }
}
</style>
