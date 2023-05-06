<template>
  <div class="q-mb-md">
    <div class="text-h6 q-mb-xs">Основные теги</div>
    <q-input
      ref="filterRef"
      filled
      v-model="filter"
      label="Search tags"
      dense
    >
      <template v-slot:append>
        <q-icon v-if="filter !== ''" name="clear" class="cursor-pointer" @click="resetFilter" />
      </template>
    </q-input>

    <q-tree
      :nodes="commonTags"
      node-key="label"
      :filter="filter"
      :filter-method="filterMethod"
    >
      <template v-slot:default-header="scope">
        <div class="flex items-center">
          <div>{{ scope.node.label }}</div>
          <div class="flex q-ml-xs">
            <q-btn
              @click.stop="addTagDialog = true"
              icon="add"
              size="xs"
              color="primary"
              dense
              flat
              rounded
            />
            <q-btn
              @click.stop="editTagDialog = true"
              icon="edit"
              size="xs"
              color="primary"
              dense
              flat
              rounded
            />
            <q-btn
              @click.stop="deleteTagDialog = true"
              icon="delete"
              size="xs"
              color="primary"
              dense
              flat
              rounded
            />
          </div>
        </div>
      </template>
    </q-tree>
  </div>

  <div class="q-mb-md">
    <div class="text-h6 q-mb-xs">Второстепенные теги</div>

    <q-tree
      :nodes="secondaryTags"
      node-key="label"
      :filter="filter"
      :filter-method="filterMethod"
    >
      <template v-slot:default-header="scope">
        <div class="flex items-center">
          <div>{{ scope.node.label }}</div>
          <div class="flex q-ml-xs">
            <q-btn
              @click.stop="addTagDialog = true"
              icon="add"
              size="xs"
              dense
              flat
              rounded
            />
            <q-btn
              @click.stop="editTagDialog = true"
              icon="edit"
              size="xs"
              dense
              flat
              rounded
            />
            <q-btn
              @click.stop="deleteTagDialog = true"
              icon="delete"
              size="xs"
              dense
              flat
              rounded
            />
          </div>
        </div>
      </template>
    </q-tree>
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

 export default {
   setup() {
     const $q = useQuasar()

     const commonTags = ref([])
     const secondaryTags = ref([])
     const filter = ref('')
     const filterRef = ref(null)
     const selected = ref('')
     const addTagDialog = ref(false)
     const editTagDialog = ref(false)
     const deleteTagDialog = ref(false)

     const filterMethod = (node, text) => {
       return node.label && node.label.toLowerCase().indexOf(text.toLowerCase()) > -1
     }

     const resetFilter = () => {
       filter.value = ''
       filterRef.value.focus()
     }

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
       filter,
       filterRef,
       selected,
       addTagDialog,
       editTagDialog,
       deleteTagDialog,
       filterMethod,
       resetFilter,
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
