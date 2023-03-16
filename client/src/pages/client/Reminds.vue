<template>
  <div class="q-mb-lg">
    <q-btn @click="showModal = true" color="primary" label="Create Remind" icon="add" />
  </div>
  <q-table
    :rows="reminds"
    :columns="columns"
    row-key="title"
    :flat="true"
    :rows-per-page-options="[0]"
    :pagination.sync="{page: 1, rowsPerPage: 0}"
    ref="remindTable"
    dense
  >
    <template v-slot:header="props">
      <q-tr :props="props">
        <q-th auto-width />
        <q-th
          v-for="col in props.cols"
          :key="col.name"
          :props="props"
        >
          {{ col.label }}
        </q-th>
      </q-tr>
    </template>

    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td auto-width>
          <q-btn size="sm" round dense @click="props.expand = !props.expand" :icon="props.expand ? 'expand_more' : 'chevron_right'" />
        </q-td>
        <q-td
          v-for="col in props.cols"
          :key="col.name"
          :props="props"
        >
          <template v-if="col.name === 'active'">
            <q-toggle
              v-model="props.row.isActive"
              @click="switchActive(props.row)"
              checked-icon="add"
              unchecked-icon="remove"
            />
          </template>
          <template v-else>
            {{ col.value }}
          </template>
        </q-td>
      </q-tr>
      <q-tr v-show="props.expand" :props="props">
        <q-td colspan="100%">
          <div class="text-left">This is expand slot for row above: {{ props.row.title }}.</div>
        </q-td>
      </q-tr>
    </template>
  </q-table>
  <q-dialog v-model="showModal">
    <q-card style="width: 700px; max-width: 80vw;">
      <q-card-section>
        <div class="text-h6">Добавить напоминание</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-form class="q-gutter-y-md column">
          <q-input
            v-model="model.title"
            label="Заголовок"
            :rules="[ val => val && val.length > 0 || 'Поле title должно быть заполнено!']"
            outlined
            dense
          />
          <q-input
            v-model="model.content"
            label="Описание"
            type="textarea"
            outlined
            dense
          />
          <q-input v-model="model.datetime" outlined filled>
            <template v-slot:prepend>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                  <q-date v-model="model.datetime" mask="YYYY-MM-DD HH:mm">
                    <div class="row items-center justify-end">
                      <q-btn label="Close" color="primary" v-close-popup flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>

            <template v-slot:append>
              <q-icon name="access_time" class="cursor-pointer">
                <q-popup-proxy transition-show="scale" transition-hide="scale" cover>
                  <q-time v-model="model.datetime" mask="YYYY-MM-DD HH:mm" format24h>
                    <div class="row items-center justify-end">
                      <q-btn label="Close" color="primary" v-close-popup flat />
                    </div>
                  </q-time>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
        </q-form>
      </q-card-section>

      <q-card-actions align="right" class="bg-white">
        <q-btn label="Create" color="primary" @click="createRemind" :loading="createRemindLoading" />
        <!--Todo: Need to write cancel update handler for returning previous values to model-->
        <q-btn label="Cancel" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
<script>
import {ref, onMounted} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

export default {
  setup() {
    const $q = useQuasar()

    const remindTable = ref(null)
    const columns = [
      { name: 'title', label: 'Title', field: 'title', align: 'left', sortable: true },
      { name: 'date', label: 'Date', field: 'datetime', align: 'center', sortable: true },
      { name: 'active', label: 'Active', field: 'isActive', align: 'center', }
    ]
    const reminds = ref([])
    const showModal = ref(false)
    const model = ref({
      title: '',
      content: '',
      datetime: '',
      is_active: true
    })
    const createRemindLoading = ref(false)

    const getReminds = async () => {
      await API.get('reminds').then(response => {
        reminds.value = response.data.reminds
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }
    const switchActive = async row => {
      await API.post(`reminds/${row.id}/update`, {
        'id': row.id,
        'is_active': row.isActive,
      }).then(response => {
        setTimeout(() => {
          reminds.value.sort((a, b) => b.isActive > a.isActive ? 1 : -1)
        }, 500)
        $q.notify({
          type: 'positive',
          message: `The status of remind has been changed!`
        })
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }
    const createRemind = async () => {
      createRemindLoading.value = true
      await API.put('reminds/store', {
        ...model.value
      }).then(response => {
        createRemindLoading.value = false
        reminds.value.unshift(response.data.reminds)
        $q.notify({
          type: 'positive',
          message: `Remind has been created`
        })
        showModal.value = false
      }).catch(error => {
        createRemindLoading.value = false
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }

    onMounted(() => {
      getReminds()
    })

    return {
      columns,
      reminds,
      remindTable,
      showModal,
      model,
      createRemindLoading,
      switchActive,
      createRemind
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
