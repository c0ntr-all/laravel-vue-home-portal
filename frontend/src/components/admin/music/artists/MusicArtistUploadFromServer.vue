<template>
  <el-button @click="handleOpenModal">Выбрать папку</el-button>
  <div class="upload-zone">
    <el-divider border-style="dashed" />
    <div class="upload-folder">
      <el-input v-model="selectedFolder" placeholder="Путь до папки с исполнителем" />
      <el-button type="primary" @click="handlerUploadFromFolder">Загрузить</el-button>
    </div>
  </div>
  <app-modal :openModal="openModal" @closeModal="openModal = false">
    <template v-slot:title>
      Выбрать папку для загрузки
    </template>
    <template v-slot:content>
      <el-tree
        v-loading="loadFolder"
        :load="loadNode"
        lazy
        :props="defaultProps"
        :expand-on-click-node="false"
        @node-click="handleNodeClick"
      />
    </template>
    <template v-slot:footer>
    </template>
  </app-modal>
</template>
<script>
import API from "@/utils/api";

import AppModal from "../../../default/AppModal";

export default {
  data() {
    return {
      openModal: false,
      selectedFolder: '',
      loadFolder: false,
      defaultFolder: 'F:\\Music\\',
      defaultProps: {
        children: 'children',
        label: 'label',
      },
    }
  },
  methods: {
    handleOpenModal() {
      this.openModal = true
      this.loadFolder = true
    },
    handleNodeClick(data, node) {
      this.selectedFolder = this.defaultFolder ? this.defaultFolder + this.getFullPath(node) : this.getFullPath(node)
    },
    async handlerUploadFromFolder() {
      const {data} = await API.post('music/upload', {
        'folder': this.selectedFolder
      })
      if(data.success) {
        this.$message.success(`Исполнитель ${data.artist} успешно загружен!`);
      }else{
        this.$message.error(data.message);
      }
    },
    async loadNode(node, resolve) {
      if(node.level === 0) {
        let list = await this.getFolder()

        if(list) {
          this.loadFolder = false
          return resolve(list)
        }
      }else{
        let path = this.defaultFolder ? this.defaultFolder + this.getFullPath(node) : this.getFullPath(node)
        let list = await this.getFolder(path)
        return resolve(list)
      }
    },
    async getFolder(folder) {
      const {data} = await API.post('folders', {
        'folder': folder || this.defaultFolder
      })
      if(data) {
        return Object.values(data).map(value => {
          return {
            label: value,
          }
        })
      }

      return false
    },
    getFullPath(node, path = '') {
      if(node.level > 1) {
        let nextPath = node.label + '\\' + path
        if(this.defaultFolder && node.level === 2) {
          nextPath = '\\' + nextPath
        }
        return this.getFullPath(node.parent, nextPath)
      }else{
        return node.label + path
      }
    },
  },
  components: {
    AppModal
  }
}
</script>

<style lang="scss" scoped>
  .upload-folder {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
</style>
