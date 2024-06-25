<template>
  <div class="q-mb-md">
    <AddTagButton @tagCreate="addTag"/>
    <q-input
      class="q-mb-md"
      ref="filterRef"
      v-model="filter"
      label="Search tags"
      dense
      filled
    >
      <template v-slot:append>
        <q-icon v-if="filter !== ''" name="clear" class="cursor-pointer" @click="resetFilter"/>
      </template>
    </q-input>

    <div class="text-h6 q-mb-xs">Основные теги</div>
    <q-tree
      :nodes="baseTags"
      node-key="name"
      :filter="filter"
      :filter-method="filterMethod"
    >
      <template v-slot:default-header="scope">
        <div class="flex items-center">
          <div>{{ scope.node.name }}</div>
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
      node-key="name"
      :filter="filter"
      :filter-method="filterMethod"
    >
      <template v-slot:default-header="scope">
        <div class="flex items-center">
          <div>{{ scope.node.name }}</div>
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
        <div class="text-h6">Create new child tag for <b>{{ tagModel.parentTag.name }}</b></div>
        <q-space/>
        <q-btn icon="close" flat round dense v-close-popup/>
      </q-card-section>

      <q-card-section>
        <div class="q-gutter-md">
          <q-input
            v-model="tagModel.name"
            ref="tagNameRef"
            placeholder="Name"
            dense
            filled
          />
          <q-input
            v-model="tagModel.description"
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
        <div class="text-h6">Edit tag <b>{{ tagModel.tag.name }}</b></div>
        <q-space/>
        <q-btn icon="close" flat round dense v-close-popup/>
      </q-card-section>

      <q-card-section>
        <div class="q-gutter-md">
          <q-input
            v-model="tagModel.tag.name"
            placeholder="Name"
            dense
            filled
          />
          <q-input
            v-model="tagModel.tag.description"
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
        <q-space/>
        <q-btn icon="close" flat round dense v-close-popup/>
      </q-card-section>

      <q-card-section>
        <p>Delete tag <b>{{ tagModel.tag.name }}</b></p>
      </q-card-section>

      <q-card-section>
        <q-btn @click="deleteTag" color="primary">Delete</q-btn>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script setup>
import {ref, onMounted, nextTick} from "vue"
import {useQuasar} from "quasar"
import {api} from "boot/axios"

import deleteFromTree from "src/utils/deleteFromTree"
import insertIntoTree from "src/utils/insertIntoTree"

import AddTagButton from "components/admin/music/tabs/tags/AddTagButton.vue"

const $q = useQuasar()

const baseTags = ref([])
const secondaryTags = ref([])
const filter = ref('')
const filterRef = ref(null)
const addTagDialog = ref(false)
const editTagDialog = ref(false)
const deleteTagDialog = ref(false)
const tagNameRef = ref(null)
const tagModel = ref({})

const addTagHandler = scope => {
  console.log(scope, scope.node)
  addTagDialog.value = true
  tagModel.value.parentTag = scope.node

  nextTick(() => {
    tagNameRef.value.$el.querySelector('input').focus()
  })
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
  return node.name && node.name.toLowerCase().indexOf(text.toLowerCase()) > -1
}

const resetFilter = () => {
  filter.value = ''
  filterRef.value.focus()
}

const getTags = async () => {
  await api.get('music/admin/tags')
    .then(response => {
      const {data} = response
      if (response.status === 200) {
        baseTags.value = data.items.base
        secondaryTags.value = data.items.secondary
      }
    }).catch(error => {
      $q.notify({
        type: 'negative',
        message: error.response.data.message
      })
    })
}

const addTag = async (tagData) => {
  tagData = tagData instanceof PointerEvent ? {
    name: tagModel.value.name,
    description: tagModel.value.description,
    parent_id: tagModel.value.parentTag?.id
  } : tagData

  await api.post('music/admin/tags', tagData).then(response => {
    const {data: {data}} = response

    if (Object.keys(tagModel.value).length !== 0) {
      insertIntoTree(
        tagModel.value.parentTag.is_base ? baseTags.value : secondaryTags.value,
        tagModel.value.parentTag.id,
        data
      )
    } else {
      if (data.is_base) {
        baseTags.value.push(data)
        baseTags.value.sort((a, b) => a.name.localeCompare(b.name));
      } else {
        secondaryTags.value.push(data)
        secondaryTags.value.sort((a, b) => a.name.localeCompare(b.name));
      }
    }

    $q.notify({
      type: 'positive',
      message: `Тег ${tagData.name} успешно добавлен!`
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
  await api.patch(`music/tags/${tagModel.value.tag.id}/update`, {
    name: tagModel.value.tag.name,
    description: tagModel.value.tag.description
  }).then(() => {
    $q.notify({
      type: 'positive',
      message: `Тег ${tagModel.value.tag.name} успешно обновлён!`
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
  await api.post(`music/tags/${tagModel.value.tag.id}/delete`).then(response => {
    deleteFromTree(tagModel.value.tag.is_base ? baseTags.value : secondaryTags.value, tagModel.value.tag.id)

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
