<template>
  <RemindsTableSkeleton v-if="loading" />

  <template v-else>
    <div class="q-mb-lg">
      <q-btn @click="createRemindModal = true" color="primary" label="Create Remind" icon="add" />
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
            <q-btn
              class="q-mr-sm"
              size="sm"
              @click="props.expand = !props.expand"
              :icon="props.expand ? 'expand_more' : 'chevron_right'"
              round
              dense
            />
            <q-btn
              @click="initRemindUpdate(props.row)"
              size="sm"
              icon="edit"
              round
              dense
            />
          </q-td>
          <q-td
            v-for="col in props.cols"
            :key="col.name"
            :props="props"
          >
            <table-col :col="col" :row="props.row" @activeSwitched="sortReminds" />
          </q-td>
        </q-tr>
        <q-tr v-show="props.expand" :props="props">
          <q-td colspan="100%">
            <div class="text-left">{{ props.row.content }}</div>
          </q-td>
        </q-tr>
      </template>
    </q-table>
    <q-dialog v-model="createRemindModal" @hide="onHideModal">
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
            <div class="q-gutter-sm">
              <q-radio v-model="model.group" val="teal" label="Teal" color="teal" />
              <q-radio v-model="model.group" val="orange" label="Orange" color="orange" />
              <q-radio v-model="model.group" val="red" label="Red" color="red" />
              <q-radio v-model="model.group" val="cyan" label="Cyan" color="cyan" />
              <q-radio v-model="model.group" val="green" label="Green" color="green" />
              <q-radio v-model="model.group" val="blue" label="Blue" color="blue" />
            </div>
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="bg-white">
          <q-btn label="Create" color="primary" @click="createRemind" :loading="createRemindLoading" />
          <!--Todo: Need to write cancel update handler for returning previous values to model-->
          <q-btn label="Cancel" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="updateRemindModal" @hide="onHideModal">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Редактировать напоминание</div>
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
            <div class="q-gutter-sm">
              <q-radio v-model="model.group" val="teal" label="Teal" color="teal" />
              <q-radio v-model="model.group" val="orange" label="Orange" color="orange" />
              <q-radio v-model="model.group" val="red" label="Red" color="red" />
              <q-radio v-model="model.group" val="cyan" label="Cyan" color="cyan" />
              <q-radio v-model="model.group" val="green" label="Green" color="green" />
              <q-radio v-model="model.group" val="blue" label="Blue" color="blue" />
            </div>
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="bg-white">
          <q-btn label="Update" color="primary" @click="updateRemind" :loading="updateRemindLoading" />
          <!--Todo: Need to write cancel update handler for returning previous values to model-->
          <q-btn label="Cancel" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </template>
</template>
<script>
import {ref, onMounted} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

import RemindsTableSkeleton from 'src/components/client/reminds/skeleton/RemindsPage.vue'
import TableCol from 'src/components/client/reminds/RemindsTableCol.vue'

export default {
  components: {
    RemindsTableSkeleton,
    TableCol
  },
  setup() {
    const $q = useQuasar()

    let loading = ref(true)
    const remindTable = ref(null)
    const columns = [
      { name: 'title', label: 'Title', field: 'title', align: 'left', sortable: true },
      { name: 'time_left', label: 'Time left', field: 'time_left', align: 'center', sortable: true },
      { name: 'date', label: 'Date', field: 'datetime', align: 'center', sortable: true },
      { name: 'active', label: 'Active', field: 'isActive', align: 'center', }
    ]
    const reminds = ref([])
    const createRemindModal = ref(false)
    const updateRemindModal = ref(false)
    const model = ref({
      title: '',
      content: '',
      group: '',
      datetime: '',
      is_active: true
    })
    const createRemindLoading = ref(false)
    const updateRemindLoading = ref(false)

    const getReminds = async () => {
      await API.get('reminds').then(response => {
        reminds.value = response.data.reminds
        loading.value = false
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
        loading.value = false
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
        createRemindModal.value = false
      }).catch(error => {
        createRemindLoading.value = false
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }

    const updateRemind = async () => {
      updateRemindLoading.value = true

      await API.patch(`reminds/${model.value.id}/update`, {
        ...model.value
      }).then(response => {
        for(let key in reminds.value) {
          if(reminds.value[key].id === response.data.data.id) {
            reminds.value[key] = response.data.data
          }
        }
        updateRemindLoading.value = false
        $q.notify({
          type: 'positive',
          message: `Remind has been updated!`
        })
        updateRemindModal.value = false
      }).catch(error => {
        updateRemindLoading.value = false
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }

    const sortReminds = () => {
      setTimeout(() => {
        reminds.value.sort((a, b) => b.isActive > a.isActive ? 1 : -1)
      }, 500)
    }

    const initRemindUpdate = remind => {
      model.value = remind

      updateRemindModal.value = true
    }

    const onHideModal = () => {
      model.value = {}
    }

    onMounted(() => {
      getReminds()
    })

    return {
      loading,
      columns,
      reminds,
      remindTable,
      createRemindModal,
      updateRemindModal,
      model,
      createRemindLoading,
      updateRemindLoading,
      createRemind,
      sortReminds,
      initRemindUpdate,
      updateRemind,
      onHideModal
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
