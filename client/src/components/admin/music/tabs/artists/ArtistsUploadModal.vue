<template>
  <AppModal v-model="show">
    <template #header>
      Загрузка исполнителей
    </template>
    <template #body>
      <div v-for="artist in artistData">
        <p class="text-h4 q-pb-md">{{ artist.name }}</p>
        <div v-for="album in artist.albums">
          <div class="q-pb-md q-gutter-md">
            <q-list class="rounded-borders" bordered padding>
              <q-item-label header>{{ album.year }} - {{ album.name }}</q-item-label>
              <q-item v-for="track in album.tracks" clickable v-ripple>
                <q-item-section avatar>
                  <q-icon v-if="track.uploaded" name="check_circle_outline" size="md" color="green" />
                  <q-icon v-else name="highlight_off" size="md" />
                </q-item-section>

                <q-item-section>
                  <q-item-label lines="1">{{ track.name }}</q-item-label>
                  <q-item-label caption>{{ track.duration }}</q-item-label>
                </q-item-section>

                <q-item-section side>
                  <q-icon name="info" color="green" />
                </q-item-section>
              </q-item>
            </q-list>
          </div>
        </div>
      </div>
    </template>
    <template #footer>
      <q-btn
        :loading="processLoading"
        @click="processUploadArtist"
        label="Загрузить"
        color="primary"
        class="q-mb-lg"
      />
    </template>
  </AppModal>
</template>

<script setup>
import {onMounted, ref, watch, watchEffect} from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";
import AppModal from "src/components/extra/AppModal.vue";

const props = defineProps(['artistData', 'fullPath', 'processLoading', 'show', 'modelValue']);
const emit = defineEmits(['trackParsed', 'update:modelValue'])

const $q = useQuasar()
let connection = null
const processLoading = ref(props.processLoading)
const show = ref(props.modelValue)

const processUploadArtist = async () => {
  // show.value = true
  // processLoading.value = true

  await api.post('music/admin/artists/upload', {
    path: props.fullPath,
    preview: false
  }).then(response => {
    $q.notify({
      type: 'positive',
      message: `Исполнители "${response.data.data.artists.join(', ')}" успешно загружены!`
    })
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Ошибка загрузки исполнителей`
    })
  }).finally(() => {
    processLoading.value = false
  })
}

watchEffect(() => {
  show.value = props.modelValue;
});

watch(show, (newVal) => {
  if (newVal !== props.modelValue) {
    emit('update:modelValue', newVal);
  }
});

onMounted(() => {
  // Pusher.logToConsole = true;
  window.Echo.channel('artist-parsing')
    .on(`track-parsed`, (data) => {
      emit('trackParsed', data.message)
    })
})
</script>

<style lang="scss" scoped>

</style>
