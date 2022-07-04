<template>
  <h3>Загрузка Банды</h3>
  <el-tabs tab-position="top">
    <el-tab-pane label="Вручную">
      <el-row :gutter="10">
        <el-col :xs="24" :sm="12" :md="10" :lg="8" :xl="5">
          <el-form :label-position="'right'">
            <el-form-item label="Название банды" prop="name">
              <el-input
                v-model="this.model.artist.name"
                maxlength="100"
                placeholder="Введите название"
                show-word-limit
                type="text"
              />
            </el-form-item>
            <el-form-item label="Описание банды" prop="content">
              <el-input type="textarea" placeholder="Описание банды..." v-model="this.model.artist.content" maxlength="10000" show-word-limit />
            </el-form-item>
            <el-form-item label="Постер">
              <div class="input-poster">
                <input type="file" id="poster" ref="poster" @change="onChangePoster"/>
              </div>
              <div class="poster-preview">
                <img v-if="posterPreview" :src="posterPreview" alt="">
              </div>
            </el-form-item>
            <el-form-item label="Теги">
              <el-tag
                v-for="tag in this.model.artist.tags"
                :key="tag"
                class="mx-1"
                closable
                :disable-transitions="false"
                @close="closeTag(tag)"
              >
                {{ tag }}
              </el-tag>
              <el-input
                v-if="tagInputVisible"
                ref="taginput"
                v-model="tagInputValue"
                class="ml-1 tag-input"
                size="small"
                @keyup.enter="tagInputConfirm"
                @blur="tagInputConfirm"
              />
              <el-button v-else class="button-new-tag ml-1" size="small" @click="showTagInput">
                + New Tag
              </el-button>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="createArtist">Создать</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-tab-pane>
    <el-tab-pane label="Автоматически">
      <el-button @click="handlerFolderModal">Выбрать папку</el-button>
      <div class="upload-zone">
        <el-divider border-style="dashed" />
        <div class="upload-folder">
          <el-input v-model="selectedFolder" placeholder="Путь до папки с исполнителем" />
          <el-button type="primary" @click="handlerUploadFromFolder">Загрузить</el-button>
        </div>
      </div>
      <app-modal :openModal="openFolderModal" @closeModal="openFolderModal = false">
        <template v-slot:title>
          Выбрать папку для загрузки
        </template>
        <template v-slot:content>
          <el-tree
            v-loading="folderLoading"
            :load="loadNode"
            lazy
            :props="this.defaultProps"
            :expand-on-click-node="false"
            @node-click="handleNodeClick"
          />
        </template>
        <template v-slot:footer>
        </template>
      </app-modal>
    </el-tab-pane>
  </el-tabs>
</template>
<script setup>
  import {
    Plus
  } from '@element-plus/icons-vue'
</script>
<script>
  import API from "../../utils/api";

  import AppModal from "../default/AppModal";

  export default {
    data() {
      return {
        posterPreview: null,
        tagInputVisible: false,
        tagInputValue: '',
        openFolderModal: false,
        folder: [],
        defaultProps: {
          children: 'children',
          label: 'label',
        },
        folderLoading: false,
        selectedFolder: '',
        defaultFolder: 'F:\\Music\\',
        model: {
          artist: {
            name: '',
            content: '',
            tags: [],
            image: null
          }
        }
      }
    },
    methods: {
      async createArtist(e) {
        const formData = new FormData();
        formData.append('name', this.model.artist.name)
        formData.append('content', this.model.artist.content)
        formData.append('tags', this.model.artist.tags)
        formData.append('image', this.model.artist.image)

        this.$store.dispatch('createMusicArtist', formData).then(result => {
          this.$message.success("Артист успешно добавлен!");

          for (let key in this.model.artist) {
            this.model.artist[key] = ''
            if(key === 'image') {
              this.$refs.poster.value = ''
            }
          }
          this.posterPreview = null
        }).catch(error => {
          this.$message.error(error);
        })
      },
      onChangePoster($event) {
        const file = event.target.files[0]
        this.model.artist.image = file
        this.posterPreview = URL.createObjectURL(file)
      },
      closeTag(tag) {
        this.model.artist.tags.splice(this.model.artist.tags.indexOf(tag), 1)
      },
      showTagInput() {
        this.tagInputVisible = true
        this.$nextTick(() => {
          this.$refs.taginput.focus()
        })
      },
      tagInputConfirm() {
        if (this.tagInputValue) {
          this.model.artist.tags.push(this.tagInputValue)
        }
        this.tagInputVisible = false
        this.tagInputValue = ''
      },
      handlerFolderModal() {
        this.openFolderModal = true
        this.folderLoading = true
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
      async loadNode(node, resolve) {
        if(node.level === 0) {
          let list = await this.getFolder()

          if(list) {
            this.folderLoading = false
            return resolve(list)
          }
        }else{
          let path = this.defaultFolder ? this.defaultFolder + this.getFullPath(node) : this.getFullPath(node)
          let list = await this.getFolder(path)
          return resolve(list)
        }
      },
      handleNodeClick(data, node) {
        this.selectedFolder = this.defaultFolder ? this.defaultFolder + this.getFullPath(node) : this.getFullPath(node)
      },
      async handlerUploadFromFolder() {
        const response = await API.post('music/upload', {
          'folder': this.selectedFolder
        })
        if(response) {
          console.log(response)
        }
      }
    },
    components: {
      AppModal
    },
    mounted() {
    }
  }
</script>

<style lang="scss">
  .el-row {
    margin-bottom: 20px;
  }
  .el-row:last-child {
    margin-bottom: 0;
  }
  .el-col {
    border-radius: 4px;
  }

  .poster-uploader {
    .poster {
      display: block;
      width: 178px;
      height: 178px;
    }
    .el-upload {
      border: 1px dashed #dcdfe6;
      border-radius: 6px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: .2s;
    }
    .el-upload:hover {
      border-color: #409eff;
    }
  }

  .el-icon.poster-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    text-align: center;
  }
  .poster-preview {
    position: relative;

    img {
      width: 100%
    }
  }
  .tag-input {
    width: 100px;
  }
  .upload-folder {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
</style>
