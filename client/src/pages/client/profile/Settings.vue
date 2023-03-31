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
      <q-card class="q-mb-md" flat bordered>
        <q-card-section class="row">
          <div class="col-lg-2">
            Группы
          </div>
          <div class="col-lg-10">
            <q-table
              class="q-mb-md"
              :rows="[
                { name: 'Teal', color: 'teal' },
                { name: 'Orange', color: 'orange' },
                { name: 'Red', color: 'red' },
                { name: 'Cyan', color: 'cyan' },
                { name: 'Green', color: 'green' },
                { name: 'Blue', color: 'blue' },
              ]"
              :columns="[
                { name: 'name', align: 'left', field: row => row.name },
                { name: 'color', align: 'center', field: 'color' }
              ]"
              row-key="name"
              hide-header
              hide-bottom
              flat
              bordered
              dense
            />
            <q-btn label="Add" color="primary" icon="add" />
          </div>
        </q-card-section>
      </q-card>
    </q-tab-panel>

    <q-tab-panel name="music" class="q-pa-none">
    </q-tab-panel>
  </q-tab-panels>
</template>

<script>
import {ref} from 'vue'

import API from '../../../utils/api'

export default {
  setup() {
    const tab = ref('reminds')

    return {
      tab
    }
  },
  data() {
    return {
      email: '',
      loading: false
    }
  },
  methods: {
    async getProfile() {
      this.loading = true

      const {data} = await API.post('me')
      if(!data) {
        throw new Error('Нет данных!')
      }
      this.email = data.email
      this.loading = false
    }
  },
  mounted() {
    this.getProfile()
  }
}
</script>
<style lang="scss" scoped>
.q-tab-panels {
  background-color: transparent;
}
</style>
