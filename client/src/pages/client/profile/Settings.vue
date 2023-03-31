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
        <q-card-section class="settings-area row">
          <div class="col-lg-2">
            Группы
          </div>
          <div class="col-lg-10">
            <table class="settings-table q-mb-lg">
              <tr v-for="row in settings" class="settings-table__row">
                <td class="settings-table__col">
                  <q-input
                    v-model="row.name"
                    :rules="[ val => val.length >= 3 || 'Please use minimum 3 characters' ]"
                    class="q-pa-none"
                    borderless
                    dense
                  />
                </td>
                <td class="settings-table__col">
                  <div class="flex items-center">
                    <div class="color-square q-mr-sm" :style="`background-color:${row.color}`"></div>
                    <q-input
                      v-model="row.color"
                      :rules="['anyColor']"
                      class="q-pa-none"
                      borderless
                      dense
                    >
                      <template v-slot:append>
                        <q-icon name="colorize" class="cursor-pointer">
                          <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                            <q-color v-model="row.color" format-model="hex" />
                          </q-popup-proxy>
                        </q-icon>
                      </template>
                    </q-input>
                  </div>
                </td>
              </tr>
            </table>
            <q-btn @click="addRow" label="Add" color="secondary" icon="add" />
          </div>
        </q-card-section>

        <q-separator />

        <q-card-section>
          <q-btn label="Save" color="primary" />
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
    const columns = ref([
      { name: 'name', align: 'left', field: row => row.name },
      { name: 'color', align: 'left', field: 'color' }
    ])
    const settings = ref([
      { name: 'Teal', color: '#008080' },
      { name: 'Orange', color: '#ffa500' },
      { name: 'Red', color: '#ff0000' },
      { name: 'Cyan', color: '#00ffff' },
      { name: 'Green', color: '#008000' },
      { name: 'Blue', color: '#0000ff' },
    ])

    const addRow = () => {
      const lastItem = settings.value[settings.value.length - 1]

      if (lastItem.name && lastItem.color) {
        settings.value.push({name: '', color: ''})
      }
    }

    return {
      columns,
      settings,
      tab,
      addRow
    }
  }
}
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
