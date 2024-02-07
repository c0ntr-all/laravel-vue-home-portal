<template>
  <div class="text-h6 q-mb-md">Загрузка исполнителей</div>
  <form @submit.prevent.stop="uploadArtist" @reset.prevent.stop="onReset" class="q-gutter-md">
    <q-btn
      type="submit"
      :loading="processLoading"
      label="Загрузить"
      color="primary"
      class="q-mb-lg"
    />
    <q-btn type="reset" label="Сбросить" class="q-mb-lg"/>
    <q-input
      v-model="fullPath"
      ref="fullPathRef"
      :rules="[
        val => !!val || 'Поле не должно быть пустым!',
        val => val.length >= 10 || 'Должно быть больше 10 символов!'
      ]"
      lazy-rules
      label="Folder path"
      class="q-mb-lg"
      outlined
      dense
    />
  </form>
  <q-tree
    :nodes="foldersTree"
    default-expand-all
    node-key="key"
    v-model:selected="selectedNode"
    @lazy-load="onLazyLoad"
    class="q-mb-lg"
  />
  <ArtistUploadModal
    :artistData=artistData
    :fullPath=fullPath
    :processLoading=processLoading
    :show=showArtistDataModal
  />
</template>
<script setup>
import { ref, onMounted } from "vue"
import { useQuasar } from "quasar"
import { api } from "boot/axios"
import ArtistUploadModal from "components/admin/music/tabs/artists/ArtistsUploadModal.vue"

const $q = useQuasar()

let startFolder = 'F:\\Music\\'

const foldersTree = ref([])
const selectedNode = ref('')
const fullPath = ref(null)
const fullPathRef = ref(null)
const artistData = ref([])
const showArtistDataModal = ref(false)
const processLoading = ref(false)

const getFolder = async (folder) => {
  const path = folder || startFolder
  await api.post('folders', {'folder': path})
    .then(response => {
      const nodes = Object.values(response.data).map(value => {
        return {
          label: value,
          lazy: true,
          level: 1,
          key: `${value}-1`
        }
      })
      if (folder) {
        return new Promise((resolve, reject) => {
          resolve(nodes)
        })
      } else {
        foldersTree.value = nodes
      }
    }).catch(error => {
      return false
    })
}

const getFullPath = (node, path = '') => {
  if(node.level > 1) {
    let nextPath = node.label + '\\' + path
    if(startFolder && node.level === 2) {
      nextPath = '\\' + nextPath
    }
    return getFullPath(node.parent, nextPath)
  }else{
    return node.label + path
  }
}

const onLazyLoad = async ({ node, key, done, fail }) => {
  const path = startFolder + getFullPath(node)

  await api.post('folders', {'folder': path})
    .then(response => {
      const nodes = Object.values(response.data).map(value => {
        return {
          label: value,
          lazy: true,
          parent: node,
          level: node.level + 1,
          key: `${value}-${node.level}`
        }
      })
      done(nodes)
    })
}

const uploadArtist = async () => {
  processLoading.value = true
  fullPathRef.value.validate()

  await api.post('music/admin/artists/upload', {
    path: fullPath.value,
    preview: true
  }).then(response => {
    if (response.data.success) {
      artistData.value = response.data.data
      showArtistDataModal.value = true
    } else {
      $q.notify({
        type: 'negative',
        message: response.data.message
      })
    }
  }).finally(() => {
    processLoading.value = false
  })
}

const onReset = () => {
  fullPath.value = null
  fullPathRef.value.resetValidation()
}

onMounted(() => {
  getFolder()
})
</script>
