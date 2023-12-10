<template>
  <div class="tracks-filter">
    <div class="text-h5 q-mb-sm">Filter</div>
    <div class="tracks-filter__params q-mb-md">
      <q-btn-toggle
        v-model="type"
        @click="checkRules"
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
        v-model="secondaryTagsModel"
        :options="secondaryTagsSelect"
        :size="'xs'"
        label="Select Style"
        style="width: 150px"
        outlined
        dense
      />
      <q-select
        label="Select Genre"
        v-model="commonTagsModel"
        input-debounce="0"
        :options="commonTagsSelect"
        style="width: 350px"
        use-input
        use-chips
        multiple
        outlined
        dense
      />
    </div>
    <div class="flex q-mb-sm q-gutter-md">
      <q-checkbox
        v-model="rate"
        val="1"
        checked-icon="sentiment_very_dissatisfied"
        unchecked-icon="sentiment_very_dissatisfied"
      />
      <q-checkbox
        v-model="rate"
        val="2"
        checked-icon="sentiment_dissatisfied"
        unchecked-icon="sentiment_dissatisfied"
      />
      <q-checkbox
        v-model="rate"
        val="3"
        checked-icon="sentiment_satisfied"
        unchecked-icon="sentiment_satisfied"
      />
      <q-checkbox
        v-model="rate"
        val="4"
        checked-icon="sentiment_very_satisfied"
        unchecked-icon="sentiment_very_satisfied"
      />
    </div>
    <div class="flex q-mb-sm q-gutter-md">
      <q-btn color="primary" label="Filter" @click="submitFilter" />
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
const rate = ref([])
const commonTagsSelect = ref([])
const secondaryTagsSelect = ref([])
const commonTagsModel = ref()
const secondaryTagsModel = ref()
const loading = ref(true)

const checkRules = async () => {
  union.value = type.value === 'strict'
}

const getTagsSelect = async () => {
  await api.post('music/tags/select').then(response => {
    const {data: {data}} = response

    commonTagsSelect.value = Object.keys(data.items.common).map(key => data.items.common[key])
    secondaryTagsSelect.value = Object.keys(data.items.secondary).map(key => data.items.secondary[key])
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  }).finally(() => {
    loading.value = false
  })
}

const submitFilter = () => {
  emit('submitFilter', {
    tags: secondaryTagsModel.value ? commonTagsModel.value.concat(secondaryTagsModel.value) : commonTagsModel.value,
    type: type.value,
    union: union.value,
    rate: rate.value
  })
}

const resetFilter = () => {
  commonTagsModel.value = []
  secondaryTagsModel.value = []
  type.value = 'strict'
  union.value = true

  emit('resetFilter')
}

onMounted(() => {
  getTagsSelect()
})
</script>
<style lang="scss">
.tracks-filter {
  position: relative;
}
.tags-toggle {
  border: 1px solid #027be3;
}
</style>
