<template>
  <div class="text-h6">Теги</div>
  <div class="q-pa-md q-gutter-sm">
    <q-input ref="filterRef" v-model="filter" label="Filter" outlined dense>
      <template v-slot:append>
        <q-icon v-if="filter !== ''" name="clear" class="cursor-pointer" @click="resetFilter" />
      </template>
    </q-input>

    <q-tree
      :nodes="tags"
      node-key="label"
      :filter="filter"
      default-expand-all
    >
      <template v-slot:default-header="prop">
        <div class="tag row justify-center items-center">
          <div class="tag__name q-ma-none">
            {{ prop.node.label }}
          </div>
          <div class="tag__actions q-gutter-x-xs">
            <q-btn @click="addTagDialog = true" size="xs" color="primary" dense>
              <q-icon name="add" size="15px"></q-icon>
            </q-btn>
            <q-btn @click="editTagDialog = true" size="xs" color="secondary" dense>
              <q-icon name="edit" size="15px"></q-icon>
            </q-btn>
            <q-btn @click="deleteTagDialog = true" size="xs" color="red" dense>
              <q-icon name="delete" size="15px"></q-icon>
            </q-btn>
          </div>
        </div>
      </template>
    </q-tree>
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

 export default {
   setup() {
     const $q = useQuasar()

     const tags = ref([])
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
