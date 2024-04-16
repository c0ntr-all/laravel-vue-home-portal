<template>
  <p>Here you can configure some settings for better convinience.</p>
  <q-tabs
    v-model="tab"
    align="left"
    class="q-mb-md"
    no-caps
    outside-arrows
    mobile-arrows
  >
    <q-tab name="reminds" label="Reminds" />
    <q-tab name="music" label="Music" />
  </q-tabs>
  <q-tab-panels
    v-model="tab"
    animated
    swipeable
    vertical
    transition-prev="jump-up"
    transition-next="jump-up"
  >
    <q-tab-panel name="reminds" class="q-pa-none">
      <RemindsTab />
    </q-tab-panel>

    <q-tab-panel name="music" class="q-pa-none">
    </q-tab-panel>
  </q-tab-panels>
</template>

<script setup>
import { ref } from "vue"
import { useQuasar } from "quasar"
import RemindsTab from "src/components/client/settings/tabs/RemindsTab.vue"

const $q = useQuasar()

const tab = ref('reminds')
const columns = ref([
  { name: 'name', align: 'left', field: row => row.name },
  { name: 'color', align: 'left', field: 'color' }
])
</script>
<style lang="scss" scoped>
.settings {
  &-area {
    max-width: 700px;
  }
  &-table {
    border-collapse: collapse;

    &__col {
      padding: 0 1rem;
      border: 1px solid #ccc;
    }
  }
}
.color-square {
  width: 10px;
  height: 10px;
}
.q-tab-panels {
  background-color: transparent;
}
</style>
