<template>
  <RemindsTableSkeleton v-if="loading" />
  <template v-else>
    <div class="q-mb-lg">
      <q-btn @click="initCreateModal" color="primary" label="Create Remind" icon="add" />
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
              :style="[props.row.group ? {'background-color': props.row.group.color} : {'background-color': 'transparent'}]"
            >
            </span>
            <q-btn
              @click="initUpdateModal(props.row)"
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
    <EditRemindModal
      v-if="showEditModal"
      v-model="showEditModal"
      :remindToUpdate="remindToUpdate"
      @updated="updateRemindInList"
      @created="insertRemindToList"
      @deleted="deleteRemindFromList"
    />
  </template>
</template>
<script setup>
import { ref, onMounted } from "vue"
import { useQuasar } from "quasar"
import { api } from "src/boot/axios"
import RemindsTableSkeleton from "src/components/client/reminds/skeleton/RemindsPage.vue"
import EditRemindModal from "src/components/client/reminds/EditRemindModal.vue"
import TableCol from "src/components/client/reminds/RemindsTableCol.vue"

const $q = useQuasar()

const loading = ref(true)
const remindTable = ref(null)
const columns = [
  { name: 'title', label: 'Title', field: 'title', align: 'left', sortable: true },
  { name: 'time_left', label: 'Time left', field: 'time_left', align: 'center' },
  { name: 'date', label: 'Date', field: 'datetime', align: 'center', sortable: true },
  { name: 'active', label: 'Active', field: 'is_active', align: 'center', }
]
const reminds = ref([])
const showEditModal = ref(false)
const remindToUpdate = ref(null)

const getReminds = async () => {
  await api.get('reminds').then(response => {
    reminds.value = response.data.items
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  }).finally(() => {
    loading.value = false
  })
}

const initCreateModal = () => {
  remindToUpdate.value = null
  showEditModal.value = true
}

const initUpdateModal = remind => {
  remindToUpdate.value = remind
  showEditModal.value = true
}

const updateRemindInList = remind => {
  reminds.value.filter(r => r.id === remind.id).map(item => {
    Object.keys(remind).forEach(key => {
      item[key] = remind[key]
    })
  })
}

const deleteRemindFromList = id => {
  reminds.value = reminds.value.filter(remind => remind.id !== id);
};

const insertRemindToList = remind => {
  reminds.value.unshift(remind)
}

const sortReminds = () => {
  setTimeout(() => {
    reminds.value.sort((a, b) => b.group > a.group ? 1 : -1)
  }, 500)
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
