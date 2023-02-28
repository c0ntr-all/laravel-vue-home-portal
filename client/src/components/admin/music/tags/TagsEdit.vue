<template>
  <div class="q-mb-md">
    <div class="text-h6 q-mb-xs">Основные теги</div>
    <q-input ref="filterRef" v-model="filter" label="Filter" outlined dense>
      <template v-slot:append>
        <q-icon v-if="filter !== ''" name="clear" class="cursor-pointer" @click="resetFilter" />
      </template>
    </q-input>
    <app-table
      :rows="commonTags"
      :columns="columns"
      row-key="id"
      expand
    />
  </div>

  <div class="q-mb-md">
    <div class="text-h6 q-mb-xs">Второстепенные теги</div>
    <app-table
      :rows="secondaryTags"
      :columns="columns"
      row-key="id"
    />
  </div>

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
</template>
<script>
 import { ref, onMounted } from 'vue'
 import { useQuasar } from "quasar"
 import API from "src/utils/api"
 import AppTable from "components/extra/table/AppTable.vue"

 export default {
   components: { AppTable },
   setup() {
     const $q = useQuasar()

     const commonTags = ref([])
     const secondaryTags = ref([])
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
           commonTags.value = response.data.tags.common
           secondaryTags.value = response.data.tags.secondary
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
       commonTags,
       secondaryTags,
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
