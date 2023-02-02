<template>
  <div class="text-h6">Теги</div>
  <div class="q-pa-md q-gutter-sm">
    <q-input ref="filterRef" v-model="filter" label="Filter" outlined dense>
      <template v-slot:append>
        <q-icon v-if="filter !== ''" name="clear" class="cursor-pointer" @click="resetFilter" />
      </template>
    </q-input>

    <app-table
      :rows="tags"
      :columns="columns"
      row-key="id"
    />

    <q-table
      title="Основные теги"
      row-key="id"
      :rows="tags"
      :columns="columns"
      :flat="true"
      :rows-per-page-options="[0]"
      :pagination.sync="{page: 1, rowsPerPage: 0}"
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

<!--      <template v-slot:body="props">-->
<!--        <recursive-table-row :props="props" />-->
<!--      </template>-->
    </q-table>
    <q-dialog v-model="addTagDialog">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Close icon</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum repellendus sit voluptate voluptas eveniet porro. Rerum blanditiis perferendis totam, ea at omnis vel numquam exercitationem aut, natus minima, porro labore.
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>
<script>
 import { ref, onMounted } from 'vue'
 import { useQuasar } from "quasar"
 import API from "src/utils/api"
 import AppTable from "components/extra/table/AppTable.vue"
 import RecursiveTableRow from 'src/components/extra/RecursiveTableRow.vue'

 export default {
   components: { AppTable, RecursiveTableRow },
   setup() {
     const $q = useQuasar()

     const tags = ref([])
     const columns = ref([{
       name: "id",
       required: true,
       label: 'ID',
       align: 'left',
       field: row => row.id,
       sortable: true,
       style: 'width: 40px'
     }, {
       name: "name",
       required: true,
       label: 'Имя',
       align: 'left',
       field: row => row.label,
       sortable: true
     }, {
       name: "createdAt",
       required: true,
       label: 'Дата добавления',
       align: 'left',
       field: row => row.createdAt,
       sortable: true
     }])
     const filter = ref('')
     const addTagDialog = ref(false)
     const editTagDialog = ref(false)
     const deleteTagDialog = ref(false)

     const getTags = async () => {
       await API.post('music/tags/tree')
         .then(response => {
            tags.value = response.data.tags.common
         }).catch(error => {
           return false
         })
     }
     const addTag = async () => {
       await API.post('music/tags/store', tag)
         .then(response => {
           $q.notify({
             type: 'positive',
             message: `Тег ${response.label} успешно добавлен!`
           })
       }).catch(error => {
           $q.notify({
             type: 'negative',
             message: error
           })
       })
     }
     const editTag = async () => {
       await API.post('music/tags/update', tag)
         .then(response => {
           $q.notify({
             type: 'positive',
             message: `Тег ${response.label} успешно обновлён!`
           })
       }).catch(error => {
           $q.notify({
             type: 'negative',
             message: error
           })
       })
     }
     const deleteTag = async () => {

     }
     onMounted(() => {
       getTags()
     })
     return {
       tags,
       columns,
       filter,
       addTagDialog,
       editTagDialog,
       deleteTagDialog,
       getTags,
       addTag,
       editTag,
       deleteTag
     }
   }
 }
</script>
<style lang="scss" scoped>
  .tag {
    &__actions {
      margin-left: 5px;
    }
  }
</style>
