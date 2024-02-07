<template>
  <AppModal v-model="props.show">
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
import Echo from "laravel-echo";
import { onMounted } from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";
import useWebSocket from "src/composables/useWebSocket";
import AppModal from "src/components/extra/AppModal.vue";

const props = defineProps(['artistData', 'fullPath', 'processLoading', 'show']);
const show = props.show

const $q = useQuasar()

// const { socket, connect, disconnect } = useWebSocket();

let connection = null
const processLoading = props.processLoading

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

onMounted(() => {
  Pusher.logToConsole = true;
  window.Echo.channel('artist-parsing')
    .on(`track-parsed`, (data) => {
      console.log(data.message)
    })



  // connect();

// Отправляем сообщение, когда соединение установлено
//   socket.value.onopen = function(event) {
//     console.log('WebSocket connection established:', event);
//
//     // Подписываемся на канал
//     socket.value.send(JSON.stringify({
//       action: 'subscribe',
//       channel: 'artist-parsing'
//     }));
//   };
//
//   socket.value.onmessage = function(event) {
//     const data = JSON.parse(event.data);
//     console.log('Message received:', data);
//
//     // Проверяем, что сообщение пришло из нужного канала
//     if (data.channel === 'artist-parsing') {
//       console.log('Folder parsed:', data.folderName);
//       // Обновите ваш интерфейс или выполните другие действия
//     }
//   };
})
</script>

<style lang="scss" scoped>

</style>
