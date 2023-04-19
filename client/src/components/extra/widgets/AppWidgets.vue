<template>
  <div class="row q-gutter-lg">
    <div v-for="widget in widgets" class="col-md-6 col-sm-12 col-lg-3">
      <component :is="widget.type" :widget="widget" />
    </div>
  </div>
</template>
<script>
import {ref, onMounted} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

/* For now, we will load all available widgets. */
import MusicWidget from 'src/components/extra/widgets/MusicWidget.vue'
import RemindsWidget from 'src/components/extra/widgets/RemindsWidget.vue'

export default {
  components: {
    MusicWidget,
    RemindsWidget
  },
  setup() {
    const $q = useQuasar()

    const widgets = ref([])

    const getWidgets = async () => {
      await API.get('widgets/get').then(response => {
        widgets.value = response.data
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server error: ${error.data.error}`
        })
      })
    }

    onMounted(() => {
      getWidgets()
    })
    return {
      widgets
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
