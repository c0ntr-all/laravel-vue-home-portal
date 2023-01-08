<template>
  <div class="music-filter">
    <div class="text-h5 q-mb-sm">Filter</div>
    <div class="music-filter__params q-mb-md">
      <q-btn-toggle
        v-model="type"
        @change="checkRules"
        class="tags-toggle"
        no-caps
        rounded
        unelevated
        toggle-color="primary"
        color="white"
        text-color="primary"
        :options="[
          {label: 'Точное совпадение', value: 'strict'},
          {label: 'Иерархический поиск', value: 'hierarchical'}
        ]"
      />
      <q-toggle
        label="Совместный"
        v-model="union"
        :disable="type !== 'strict'"
      />
    </div>
    <div class="flex q-mb-sm q-gutter-md">
      <q-select
        v-model="secondaryModel"
        :options="secondaryTags"
        :size="'xs'"
        label="Select Style"
        style="width: 250px"
        filled
      />
      <q-select
        label="Select Genre"
        v-model="commonModel"
        input-debounce="0"
        :options="commonTags"
        style="width: 250px"
        use-input
        use-chips
        multiple
        filled
      />
      <q-btn color="primary" label="Filter" @click="submitFilter" />
    </div>
  </div>
</template>
<script>
// import {mapActions} from 'vuex'

import {ref} from "vue";
import API from "src/utils/api";

export default {
  setup() {
    const type = ref('strict')
    const union = ref(true)
    const commonTags = ref([])
    const secondaryTags = ref([])
    const commonModel = ref()
    const secondaryModel = ref()

    const checkRules = async () => {
      this.union = this.type === 'strict'
    }

    const getArtists = async () => {
      context.commit('SET_LOADING', {
        entity: 'artists',
        value: true
      })

      const loadMore = payload.loadMore
      delete payload.loadMore

      let hasPages = context.state.music.artists.pagination.hasPages;

      if (loadMore && hasPages) {
        let url = context.state.music.artists.pagination.nextPageUrl;
        let obUrl = new URL(url)
        payload.cursor = obUrl.searchParams.get("cursor")
      }

      const {data} = await API.post('music/artists/get', payload)
      if(!data) {
        throw new Error('Нет данных!')
      }
      context.commit('SET_LOADING', {
        entity: 'artists',
        value: false
      })

      const mutation = loadMore ? 'PUSH_ARTISTS' : 'SET_ARTISTS'

      context.commit(mutation, data.artists)
      context.commit('SET_PAGINATION', data.pagination)
    }

    const getTagsSelect = async () => {
      const {data} = await API.post('music/tags/select')
      commonTags.value = Object.keys(data.tags.common).map(key => data.tags.common[key])
      secondaryTags.value = Object.keys(data.tags.secondary).map(key => data.tags.secondary[key])
    }

    const submitFilter = () => {
      getArtists({
        filters: {
          tags: this.model.secondary ? this.model.common.concat(this.model.secondary) : this.model.common,
          type: this.type,
          union: this.union
        }
      })
    }

    return {
      type,
      union,
      commonTags,
      secondaryTags,
      commonModel,
      secondaryModel,
      checkRules,
      getArtists,
      getTagsSelect,
      submitFilter
    }
  },
  mounted() {
    this.getTagsSelect()
  }
}
</script>
<style lang="scss">
.tags-toggle {
  border: 1px solid #027be3;
}
</style>
