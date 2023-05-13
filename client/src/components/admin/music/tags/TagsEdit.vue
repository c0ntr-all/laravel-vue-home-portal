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

  <q-dialog v-model="addTagDialog">
    <q-card class="tag-dialog">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Create new child tag for <b>{{ addTagModel.parentTag.label }}</b></div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <div class="q-gutter-md">
          <q-input
            v-model="addTagModel.name"
            placeholder="Name"
            dense
            filled
          />
          <q-input
            v-model="addTagModel.name"
            placeholder="Description"
            type="textarea"
            dense
            filled
          />
        </div>
      </q-card-section>

      <q-card-section>
        <q-btn>Create</q-btn>
      </q-card-section>
    </q-card>
  </q-dialog>

  <q-dialog v-model="editTagDialog">
    <q-card class="tag-dialog">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Edit tag <b>{{ addTagModel.tag.label }}</b></div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <div class="q-gutter-md">
          <q-input
            v-model="addTagModel.name"
            placeholder="Name"
            dense
            filled
          />
          <q-input
            v-model="addTagModel.name"
            placeholder="Description"
            type="textarea"
            dense
            filled
          />
        </div>
      </q-card-section>

      <q-card-section>
        <q-btn>Save</q-btn>
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
        <p>Delete tag <b>{{ addTagModel.tag.label }}</b></p>
      </q-card-section>

      <q-card-section>
        <q-btn>Delete</q-btn>
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
     const addTagDialog = ref(false)
     const editTagDialog = ref(false)
     const deleteTagDialog = ref(false)

     const addTagModel = ref({})

     const addTagHandler = scope => {
       addTagDialog.value = true
       addTagModel.value.parentTag = scope.node
     }

     const editTagHandler = scope => {
       editTagDialog.value = true
       addTagModel.value.tag = scope.node
     }

     const deleteTagHandler = scope => {
       deleteTagDialog.value = true
       addTagModel.value.tag = scope.node
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
       addTagDialog,
       editTagDialog,
       deleteTagDialog,
       addTagModel,
       addTagHandler,
       editTagHandler,
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
