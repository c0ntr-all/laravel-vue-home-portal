<template>
  <div class="q-mb-md">
    <div class="text-h6 q-mb-xs">Основные теги</div>
    <q-input
      ref="filterRef"
      v-model="filter"
      label="Search tags"
      dense
      filled
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
              @click.stop="addTagHandler(scope)"
              icon="add"
              size="xs"
              color="primary"
              dense
              flat
              rounded
            />
            <q-btn
              @click.stop="editTagHandler(scope)"
              icon="edit"
              size="xs"
              color="primary"
              dense
              flat
              rounded
            />
            <q-btn
              @click.stop="deleteTagHandler(scope)"
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
              @click.stop="addTagHandler(scope)"
              icon="add"
              size="xs"
              dense
              flat
              rounded
            />
            <q-btn
              @click.stop="editTagHandler(scope)"
              icon="edit"
              size="xs"
              dense
              flat
              rounded
            />
            <q-btn
              @click.stop="deleteTagHandler(scope)"
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

  <q-dialog v-model="addTagDialog" @hide="clearTagModel">
    <q-card class="tag-dialog">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Create new child tag for <b>{{ tagModel.parentTag.label }}</b></div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <div class="q-gutter-md">
          <q-input
            v-model="tagModel.name"
            placeholder="Name"
            dense
            filled
          />
          <q-input
            v-model="tagModel.content"
            placeholder="Description"
            type="textarea"
            dense
            filled
          />
        </div>
      </q-card-section>

      <q-card-section>
        <q-btn @click="addTag" color="primary">Create</q-btn>
      </q-card-section>
    </q-card>
  </q-dialog>

  <q-dialog v-model="editTagDialog" @hide="clearTagModel">
    <q-card class="tag-dialog">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Edit tag <b>{{ tagModel.tag.label }}</b></div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <div class="q-gutter-md">
          <q-input
            v-model="tagModel.tag.label"
            placeholder="Name"
            dense
            filled
          />
          <q-input
            v-model="tagModel.tag.content"
            placeholder="Description"
            type="textarea"
            dense
            filled
          />
        </div>
      </q-card-section>

      <q-card-section>
        <q-btn @click="editTag" color="primary">Save</q-btn>
      </q-card-section>
    </q-card>
  </q-dialog>

  <q-dialog v-model="deleteTagDialog">
    <q-card class="tag-dialog">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Are you sure?</div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <p>Delete tag <b>{{ tagModel.tag.label }}</b></p>
      </q-card-section>

      <q-card-section>
        <q-btn @click="deleteTag" color="primary">Delete</q-btn>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script>
 import { ref, onMounted } from "vue"
 import { useQuasar } from "quasar"
 import API from "src/utils/api"
 import deleteFromTree from "src/utils/deleteFromTree"

 export default {
   setup() {
     const $q = useQuasar()

     const commonTags = ref([])
     const secondaryTags = ref([])
     const filter = ref('')
     const filterRef = ref(null)
     const addTagDialog = ref(false)
     const editTagDialog = ref(false)
     const deleteTagDialog = ref(false)

     const tagModel = ref({})

     const addTagHandler = scope => {
       addTagDialog.value = true
       tagModel.value.parentTag = scope.node
     }

     const editTagHandler = scope => {
       editTagDialog.value = true
       tagModel.value.tag = scope.node
     }

     const deleteTagHandler = scope => {
       deleteTagDialog.value = true
       tagModel.value.tag = scope.node
     }

     const clearTagModel = () => {
       tagModel.value = {}
     }

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
           $q.notify({
             type: 'negative',
             message: error.response.data.message
           })
         })
     }

     const addTag = async () => {
       await API.put('music/tags/store', {
         name: tagModel.value.name,
         content: tagModel.value.tag.content,
         parent_id: tagModel.value.parentTag.id
       }).then(response => {
           $q.notify({
             type: 'positive',
             message: `Тег ${response.data.tag.label} успешно добавлен!`
           })

           clearTagModel()
           addTagDialog.value = false
       }).catch(error => {
           $q.notify({
             type: 'negative',
             message: error.response.data.message
           })
       })
     }

     const editTag = async () => {
       await API.patch(`music/tags/${tagModel.value.tag.id}/update`, {
         name: tagModel.value.tag.label,
         content: tagModel.value.tag.content
       }).then(() => {
           $q.notify({
             type: 'positive',
             message: `Тег ${tagModel.value.tag.label} успешно обновлён!`
           })

           clearTagModel()
           editTagDialog.value = false
       }).catch(error => {
           $q.notify({
             type: 'negative',
             message: error.response.data.message
           })
       })
     }

     const deleteTag = async () => {
       await API.post(`music/tags/${tagModel.value.tag.id}/delete`)
       .then(response => {
         deleteFromTree(tagModel.value.tag.common ? commonTags.value : secondaryTags.value, tagModel.value.tag.id)

         $q.notify({
           type: 'positive',
           message: `Тег ${response.data.tag} успешно удалён!`
         })

         clearTagModel()
         deleteTagDialog.value = false
       }).catch(error => {
         $q.notify({
           type: 'negative',
           message: error.response.data.message
         })
       })
     }

     onMounted(() => {
       getTags()
     })
     return {
       commonTags,
       secondaryTags,
       filter,
       filterRef,
       addTagDialog,
       editTagDialog,
       deleteTagDialog,
       tagModel,
       addTagHandler,
       editTagHandler,
       deleteTagHandler,
       clearTagModel,
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

    &-dialog {
      min-width: 500px;
    }
  }
</style>
