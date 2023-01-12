<template>
  <q-page class="q-pa-lg">
    <q-btn type="primary" :to="'/music'">Вернуться назад</q-btn>
      <div class="tag-head">
        <div class="tag-head__info">
          <h3>{{ tag.name }}</h3>
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
    const getTag = async () => {
      const {data} = await API.post('tags', {slug: this.slug})
      tag.value = data
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
    this.getTag();
  }
}
</script>

<style lang="scss" scoped>
</style>
