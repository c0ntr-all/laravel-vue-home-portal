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
        v-model="secondaryModel"
        :options="secondaryTags"
        :size="'xs'"
        label="Select Style"
        style="width: 150px"
        outlined
        dense
      />
      <q-select
        label="Select Genre"
        v-model="commonModel"
        input-debounce="0"
        :options="commonTags"
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
<script>
import { ref } from "vue"

import API from "src/utils/api"

export default {
  emits: ['submitFilter'],
  setup(props, {emit}) {
    const type = ref('strict')
    const union = ref(true)
    const rate = ref([])
    const commonTags = ref([])
    const secondaryTags = ref([])
    const commonModel = ref()
    const secondaryModel = ref()
    const loading = ref(true)

    const checkRules = async () => {
      union.value = type.value === 'strict'
    }

    const getTagsSelect = async () => {
      await API.post('music/tags/select').then(response => {
        commonTags.value = Object.keys(response.data.tags.common).map(key => response.data.tags.common[key])
        secondaryTags.value = Object.keys(response.data.tags.secondary).map(key => response.data.tags.secondary[key])

        loading.value = false
      })
    }

    const submitFilter = () => {
      emit('submitFilter', {
        filters: {
          tags: secondaryModel.value ? commonModel.value.concat(secondaryModel.value) : commonModel.value,
          type: type.value,
          union: union.value,
          rate: rate.value
        }
      })
    }

    return {
      type,
      union,
      rate,
      commonTags,
      secondaryTags,
      commonModel,
      secondaryModel,
      loading,
      checkRules,
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
.tracks-filter {
  position: relative;
}
.tags-toggle {
  border: 1px solid #027be3;
}
</style>
