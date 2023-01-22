<template>
  <q-input v-model="path" label="Path to artist folder" outlined dense />
  <q-tree
    :nodes="data"
    default-expand-all
    node-key="key"
    v-model:selected="selectedNode"
    @lazy-load="onLazyLoad"
  />
</template>
<script>
import {ref} from "vue";
import API from "src/utils/api";

export default {
  setup() {
    let startFolder = 'F:\\Music\\'

    const data = ref([])
    const selectedNode = ref('')

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

    return {
      path: ref(''),
      data,
      selectedNode,
      onLazyLoad,
      getFolder
    }
  },
  mounted() {
    this.getFolder()
  }
}
</script>
