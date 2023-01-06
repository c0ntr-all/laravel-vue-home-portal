<template>
  <q-page padding>
    <div class="column q-gutter-md">
      <q-card v-for="item in finances" class="finances-card" flat bordered>
        <q-card-section horizontal class="flex justify-between">
          <q-card-section class="flex column justify-between">
            <p class="text-grey-5">{{ item.doneAt }}</p>
            <p>{{ item.comment }}</p>
            <p
              class="text-subtitle2 text-weight-bolder q-mb-none"
              :class="{
                'text-red-6': item.type === 'outcome',
                'text-green-6': item.type === 'income'
              }"
            >
              {{ item.summ }}
            </p>
          </q-card-section>
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
      const {data} = await API.get('finances')
      finances.value = data.finances
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
