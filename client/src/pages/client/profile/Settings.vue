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
              <tr v-for="(row, index) in settings" class="settings-table__row">
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
                <td>
                  <q-btn @click="removeRow(index)" class="q-ml-sm" icon="close" flat dense round />
                </td>
              </tr>
            </table>
            <q-btn @click="addRow" label="Add" color="secondary" icon="add" />
          </div>
        </q-card-section>

        <q-separator />

        <q-card-section>
          <q-btn @click="saveSettings" label="Save" color="primary" />
        </q-card-section>
      </q-card>
    </q-tab-panel>

    <q-tab-panel name="music" class="q-pa-none">
    </q-tab-panel>
  </q-tab-panels>
</template>

<script>
import {ref, onMounted} from 'vue'
import {useQuasar} from "quasar"

import API from '../../../utils/api'

export default {
  setup() {
    const $q = useQuasar()

    let loading = ref(true)
    const tab = ref('reminds')
    const columns = ref([
      { name: 'name', align: 'left', field: row => row.name },
      { name: 'color', align: 'left', field: 'color' }
    ])
    const settings = ref([])

    const addRow = () => {
      const lastItem = settings.value[settings.value.length - 1]

      if (lastItem.name && lastItem.color) {
        settings.value.push({name: '', color: ''})
      }
    }

    const removeRow = index => {
      settings.value.splice(index, 1)
    }

    const getSettings = async () => {
      await API.get('user/settings').then(response => {
        settings.value = response.data.value
        loading.value = false
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
        loading.value = false
      })
    }

    const saveSettings = async () => {
      const remindsSettings = {
        model: 'reminds',
        key: 'groups',
        value: settings.value
      }
      await API.patch('user/settings/update', {
        'settings': remindsSettings
      }).then(response => {
        $q.notify({
          type: 'positive',
          message: 'Настройки успешно сохранены!'
        })
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server error: ${error.data.message}`
        })
      })
    }

    onMounted(() => {
      getSettings()
    })

    return {
      columns,
      settings,
      tab,
      addRow,
      removeRow,
      saveSettings
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
