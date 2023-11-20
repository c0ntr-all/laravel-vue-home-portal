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
        label="Select Styles"
        v-model="secondaryTagsModel"
        :options="secondaryTagsSelect"
        input-debounce="0"
        style="width: 100%"
        use-input
        use-chips
        multiple
        outlined
        dense
      />
      <q-select
        label="Select Genre"
        v-model="commonTagsModel"
        :options="commonTagsSelect"
        input-debounce="0"
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
<script setup>
import { ref, onMounted } from "vue"
import { useQuasar } from "quasar"
import { api } from "boot/axios"

const emit = defineEmits(['resetFilter', 'submitFilter'])
const $q = useQuasar()

const type = ref('strict')
const union = ref(true)
const commonTagsSelect = ref([])
const secondaryTagsSelect = ref([])
const commonTagsModel = ref([])
const secondaryTagsModel = ref([])
const loading = ref(true)

const setUnion = async () => {
  union.value = type.value === 'hierarchical' ? false : union.value
}

const getTagsSelect = async () => {
  await api.post('music/tags/select').then(response => {
    const {data: {data}} = response

    commonTagsSelect.value = Object.keys(data.items.common).map(key => data.items.common[key])
    secondaryTagsSelect.value = Object.keys(data.items.secondary).map(key => data.items.secondary[key])
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Something wrong with loading tags for selects: ${error.response.data.message}`
    })
  }).finally(() => {
    loading.value = false
  })
}

const commonTagsFilter = (val, update) => {
  const params = commonTags.value.map(item => {
    return item.label
  })

  update(() => {
    const needle = val.toLowerCase()
    commonOptions.value = params.filter(tag => tag.toLowerCase().indexOf(needle) > -1)
  })
}

const resetFilter = () => {
  commonTagsModel.value = []
  secondaryTagsModel.value = []
  type.value = 'strict'
  union.value = true

  emit('resetFilter')
}

const submitFilter = () => {
  let tags = []
  const array = commonTagsModel.value.concat(secondaryTagsModel.value)
  for (const key in array) {
    tags.push(array[key].value)
  }

  const filters =  {
    music_tags: {
      tags: tags,
      type: type.value,
      union: union.value
    },
  }
  emit('submitFilter', {
    filters
  })
}

onMounted(() => {
  getTagsSelect()
})
</script>
<style lang="scss">
  .music-filter {
    position: relative;
  }
  .tags-toggle {
    border: 1px solid #027be3;
  }
</style>
