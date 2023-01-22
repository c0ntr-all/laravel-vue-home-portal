<template>
  <q-input v-model="path" label="Path to artist folder" outlined dense />
  <q-tree
    :nodes="data"
    default-expand-all
    node-key="label"
    v-model:selected="selectedNode"
    @lazy-load="onLazyLoad"
  />
</template>
<script>
import {ref} from "vue";
import API from "src/utils/api";

export default {
  setup() {
    const defaultFolder = 'F:\\Music\\'

    const data = ref([])
    const selectedNode = ref('')

    const getFolder = async (folder) => {
      const path = folder || defaultFolder
      await API.post('folders', {'folder': path})
        .then(response => {
          const nodes = Object.values(response.data).map(value => {
            return {
              label: value,
              lazy: true
            }
          })
          if (folder) {
            return nodes
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
        if(defaultFolder.value && node.level === 2) {
          nextPath = '\\' + nextPath
        }
        return this.getFullPath(node.parent, nextPath)
      }else{
        return node.label + path
      }
    }

    const onLazyLoad = async ({ node, key, done, fail }) => {
      // if (key.indexOf('Lazy load empty') > -1) {
      //   done([])
      //   return
      // }

      const label = `${defaultFolder}${node.label}`
      await getFolder(label).then(response => {
        console.log(response)
        done(response)
      })
      // done([
      //   { label: `${label}.1` },
      //   { label: `${label}.2`, lazy: true },
      //   {
      //     label: `${label}.3`,
      //     children: [
      //       { label: `${label}.3.1`, lazy: true },
      //       { label: `${label}.3.2`, lazy: true }
      //     ]
      //   }
      // ])
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
