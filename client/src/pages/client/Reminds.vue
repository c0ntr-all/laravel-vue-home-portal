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
        <q-tr :props="props" class="remind">
          <q-td class="remind__col" auto-width>
            <span
              v-if="props.row.group"
              class="remind__group"
              :style="[props.row.group ? {'background-color': props.row.group} : {'background-color': 'transparent'}]"
            >
            </span>
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
            <table-col :col="col" :row="props.row" />
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
            <div class="justify-start">
              <q-toggle
                v-model="model.is_active"
                checked-icon="add"
                unchecked-icon="remove"
                label="Active"
                left-label
              />
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

            <q-editor v-model="model.content" min-height="5rem" />

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
            <div class="relative-position q-gutter-sm" style="height: 48px">
              <q-radio
                v-for="group in groups"
                :key="group.color"
                v-model="model.group"
                :val="group.color"
                :label="group.name"
                :color="group.color"
              />
              <q-inner-loading :showing="getGroupsLoading" />
            </div>
            <div class="justify-start">
              <q-toggle
                v-model="model.is_active"
                checked-icon="add"
                unchecked-icon="remove"
                label="Active"
                left-label
              />
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
<script setup>
import { ref, onMounted } from "vue"
import { useQuasar } from "quasar"
import { api } from "src/boot/axios"
import RemindsTableSkeleton from "src/components/client/reminds/skeleton/RemindsPage.vue"
import TableCol from "src/components/client/reminds/RemindsTableCol.vue"

const $q = useQuasar()

let loading = ref(true)
const remindTable = ref(null)
const columns = [
  { name: 'title', label: 'Title', field: 'title', align: 'left', sortable: true },
  { name: 'time_left', label: 'Time left', field: 'time_left', align: 'center' },
  { name: 'date', label: 'Date', field: 'datetime', align: 'center', sortable: true },
  { name: 'active', label: 'Active', field: 'is_active', align: 'center', }
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
const getGroupsLoading = ref(false)
const groups = ref([])

const getReminds = async () => {
  await api.get('reminds').then(response => {
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

  await api.post('reminds/store', {
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

  await api.patch(`reminds/${model.value.id}`, {
    ...model.value
  }).then(response => {
    for(let key in reminds.value) {
      if(reminds.value[key].id === response.data.data.id) {
        reminds.value[key] = response.data.data
      }
    }
    $q.notify({
      type: 'positive',
      message: `Remind has been updated!`
    })
    updateRemindModal.value = false
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  }).finally(() => {
    updateRemindLoading.value = false
  })
}

const sortReminds = () => {
  setTimeout(() => {
    reminds.value.sort((a, b) => b.group > a.group ? 1 : -1)
  }, 500)
}

const initRemindUpdate = async remind => {
  updateRemindModal.value = true

  model.value = remind
  if (!groups.value.length) {
    getGroupsLoading.value = true

    await api.get('user/settings').then(response => {
      groups.value = response.data.data.value
    }).catch(error => {
      $q.notify({
        type: 'negative',
        message: 'There is a problem with loading groups!'
      })
    })

    getGroupsLoading.value = false
  }
}

const onHideModal = () => {
  model.value = {}
}

onMounted(() => {
  getReminds()
})
</script>
<style lang="scss" scoped>
  .remind {
    position: relative;

    &__group {
      position: absolute;
      left: 0;
      top: 0;
      width: 4px;
      height: 100%;
      padding: 0 !important;
      border-radius: 0 50% 50% 0;
    }
  }
</style>
