<template>
  <q-btn type="primary" :to="'/music'">Вернуться назад</q-btn>
  <div class="tag-head">
    <div class="tag-head__info">
      <div class="text-h5">{{ tag.label }}</div>
    </div>
  </div>
  <div class="tag-content">
    {{ tag.content }}
  </div>
</template>
<script setup>
import { ref, onMounted} from "vue"
import { api } from "src/boot/axios"

const props = defineProps({
  'slug': String
})

const loading = ref(true)
const tag = ref([])

const getTag = async (slug) => {
  await api.get(`music/tags/${slug}`).then(response => {
    tag.value = response.data
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
  getTag(props.slug);
})
</script>

<style lang="scss" scoped>
</style>
