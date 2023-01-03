<template>
  <q-page padding>
    <div class="column q-gutter-md">
      <q-card v-for="number in [1,2,3,4,5]" class="finances-card" flat bordered>
        <q-card-section horizontal>
          <q-card-section>test {{ number }}</q-card-section>
          <q-card-actions vertical class="justify-around q-px-md">
            <q-btn flat round color="red" icon="favorite" />
            <q-btn flat round color="accent" icon="bookmark" />
            <q-btn flat round color="primary" icon="share" />
          </q-card-actions>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import { ref } from 'vue'

import API from '../utils/api'

export default {
  setup() {
    const finances = ref([])
    const getFinances = async () => {
      finances.value = await API.get('finances')
    }

    return {
      finances,
      getFinances
    }
  },
  mounted() {
    this.getFinances()
  }
}
</script>
