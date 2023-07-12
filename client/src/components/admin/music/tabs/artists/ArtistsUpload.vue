<template>
  <div class="text-h6 q-mb-md">Загрузка исполнителей</div>
  <form @submit.prevent.stop="uploadArtist" @reset.prevent.stop="onReset" class="q-gutter-md">
    <q-btn type="submit" label="Загрузить" color="primary" class="q-mb-lg"/>
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
    :nodes="data"
    default-expand-all
    node-key="key"
    v-model:selected="selectedNode"
    @lazy-load="onLazyLoad"
    class="q-mb-lg"
  />
</template>
<script>
import { ref } from "vue"
import { useQuasar } from "quasar"
import { api } from "boot/axios"

export default {
  setup() {
    const $q = useQuasar()

    let startFolder = 'F:\\Music\\'

    const data = ref([])
    const selectedNode = ref('')
    const fullPath = ref(null)
    const fullPathRef = ref(null)

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
            data.value = nodes
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
      fullPathRef.value.validate()

      await api.post('music/upload', {'folder': fullPath.value}).then(response => {
        if(response.data.success) {
          $q.notify({
            type: 'positive',
            message: `Исполнитель ${response.data.artist} успешно загружен!`
          })
        }else{
          $q.notify({
            type: 'negative',
            message: response.data.message
          })
        }
      })
    }

    const onReset = () => {
      fullPath.value = null
      fullPathRef.value.resetValidation()
    }

    return {
      data,
      selectedNode,
      fullPath,
      fullPathRef,
      getFolder,
      onLazyLoad,
      uploadArtist,
      onReset
    }
  },
  mounted() {
    this.getFolder()
  }
}
</script>
