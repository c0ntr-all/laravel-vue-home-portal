<template>
  <q-page class="q-pa-lg">
    <q-btn type="primary" :to="'/music'">Вернуться назад</q-btn>
      <div class="tag-head">
        <div class="tag-head__info">
          <div class="text-h5">{{ tag.label }}</div>
        </div>
      </div>
      <div class="tag-content">
        {{ tag.content }}
      </div>
  </q-page>
</template>
<script>
import {ref} from 'vue'
import API from "src/utils/api"

export default {
  setup() {
    const loading = ref(true)
    const tag = ref([])
    const getTag = async (slug) => {
      const {data} = await API.post('music/tags', {slug: slug})
      tag.value = data.tags
    }

    return {
      loading,
      tag,
      getTag
    }
  },
  props: {
    'slug': String
  },
  mounted() {
    this.getTag(this.slug);
  }
}
</script>

<style lang="scss" scoped>
</style>
