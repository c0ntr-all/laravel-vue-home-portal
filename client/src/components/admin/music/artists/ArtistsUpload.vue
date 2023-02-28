<template>
  <div class="text-h6 q-mb-md">Загрузка исполнителей</div>
  <q-btn @click="uploadArtist" label="Загрузить" color="primary" class="q-mb-lg"/>
  <q-input v-model="fullPath" label="Folder path" class="q-mb-lg" outlined dense />
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
import { ref } from "vue";
import { useQuasar } from "quasar";
import API from "src/utils/api";

export default {
  setup() {
    const $q = useQuasar()

    let startFolder = 'F:\\Music\\'

    const data = ref([])
    const selectedNode = ref('')
    const fullPath = ref('')

    const getFolder = async (folder) => {
      const path = folder || startFolder
      await API.post('folders', {'folder': path})
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

      await API.post('folders', {'folder': path})
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
      await API.post('music/upload', {'folder': fullPath.value}).then(response => {
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

    return {
      data,
      selectedNode,
      fullPath,
      getFolder,
      onLazyLoad,
      uploadArtist
    }
  },
  mounted() {
    this.getFolder()
  }
}
</script>
