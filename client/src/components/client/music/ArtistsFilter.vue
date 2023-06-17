<template>
  <div class="music-filter">
    <div class="text-h5 q-mb-sm">Filter</div>
    <div class="music-filter__params q-mb-md">
      <q-btn-toggle
        v-model="type"
        @click="setUnion"
        class="border-grey"
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
      <div class="flex items-center">
        <span>ИЛИ</span>
        <q-toggle
          label="И"
          v-model="union"
          :disable="type !== 'strict'"
          color="primary"
          keep-color
        />
      </div>
    </div>
    <div class="flex q-mb-sm q-gutter-md">
      <q-select
        v-model="secondaryModel"
        :options="secondaryTags"
        :size="'xs'"
        label="Select Style"
        style="width: 100%"
        outlined
        dense
      />
      <q-select
        label="Select Genre"
        v-model="commonModel"
        input-debounce="0"
        :options="commonTags"
        style="width: 100%"
        use-input
        use-chips
        multiple
        outlined
        dense
      />
      <div class="flex justify-end q-gutter-md" style="width: 100%">
        <q-btn class="q-mt-none q-ml-none" color="grey" label="Reset" @click="resetFilter" outline />
        <q-btn class="q-mt-none" color="primary" label="Filter" @click="submitFilter" />
      </div>
    </div>

    <q-inner-loading :showing="loading">
      <q-spinner-gears size="50px" color="primary" />
    </q-inner-loading>
  </div>
</template>
<script>
import { ref, onMounted } from "vue"
import { useQuasar } from "quasar"

import API from "src/utils/api"

export default {
  emits: ['resetFilter', 'submitFilter'],
  setup(props, {emit}) {
    const $q = useQuasar()

    const type = ref('strict')
    const union = ref(true)
    const commonTags = ref([])
    const secondaryTags = ref([])
    const commonModel = ref()
    const secondaryModel = ref()
    const loading = ref(true)

    const setUnion = async () => {
      union.value = type.value === 'hierarchical' ? false : union.value
    }

    const getTagsSelect = async () => {
      await API.post('music/tags/select').then(response => {
        commonTags.value = Object.keys(response.data.tags.common).map(key => response.data.tags.common[key])
        secondaryTags.value = Object.keys(response.data.tags.secondary).map(key => response.data.tags.secondary[key])
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Something wrong with loading tags for selects: ${error.response.data.message}`
        })
      }).finally(() => {
        loading.value = false
      })
    }

    const resetFilter = () => {
      commonModel.value = []
      secondaryModel.value = []
      type.value = 'strict'
      union.value = true

      emit('resetFilter')
    }

    const submitFilter = () => {
      emit('submitFilter', {
        filters: {
          tags: secondaryModel.value ? commonModel.value.concat(secondaryModel.value) : commonModel.value,
          type: type.value,
          union: union.value
        }
      })
    }

    onMounted(() => {
      getTagsSelect()
    })

    return {
      type,
      union,
      commonTags,
      secondaryTags,
      commonModel,
      secondaryModel,
      loading,
      setUnion,
      getTagsSelect,
      resetFilter,
      submitFilter
    }
  }
}
</script>
<style lang="scss">
  .music-filter {
    position: relative;
  }
  .tags-toggle {
    border: 1px solid #027be3;
  }
</style>
