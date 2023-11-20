<template>
  <q-card>
    <q-card-section>
      <div class="text-h4 q-mb-md">Genres</div>
      <div class="tags q-mb-lg">
        <div class="tags-list q-gutter-sm">
          <q-btn
            v-for="tag in tags.common"
            :key="tag.id"
            :label="tag.label"
            :to="'/music/tags/' + tag.slug"
            color="primary"
            size="sm"
            rounded
            unelevated
          />
          <q-inner-loading :showing="loading">
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useQuasar } from "quasar"
import { api } from "boot/axios"

const $q = useQuasar()

const tags = ref([])
let loading = ref(true)

const getTags = async () => {
  await api.post('music/tags/select').then(response => {
    tags.value = response.data.data.items
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: error.response.data.message
    })
  }).finally(() => {
    loading.value = false
  })
}

onMounted(() => {
  getTags()
})
</script>

<style scoped>

</style>
