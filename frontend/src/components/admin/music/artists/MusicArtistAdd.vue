<template>
  <el-row :gutter="10">
    <el-col :xs="24" :sm="12" :md="10" :lg="8" :xl="5">
      <el-form :label-position="'right'">
        <el-form-item label="Название" prop="name">
          <el-input
            v-model="this.model.name"
            maxlength="100"
            placeholder="Введите название"
            show-word-limit
            type="text"
          />
        </el-form-item>
        <el-form-item label="Описание" prop="content">
          <el-input type="textarea" placeholder="Введите писание..." v-model="this.model.content" maxlength="10000" show-word-limit />
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
            v-for="tag in this.model.tags"
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
</template>
<script>
export default {
  data() {
    return {
      model: {
        name: '',
        content: '',
        tags: [],
        image: null
      },
      posterPreview: null,
      tagInputVisible: false,
      tagInputValue: '',
      folder: [],
    }
  },
  methods: {
    async createArtist(e) {
      const formData = new FormData();
      formData.append('name', this.model.name)
      formData.append('content', this.model.content)
      formData.append('tags', this.model.tags)
      formData.append('image', this.model.image)

      this.$store.dispatch('createMusicArtist', formData).then(result => {
        this.$message.success("Исполнитель успешно добавлен!");

        for (let key in this.artist) {
          this.artist[key] = ''
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
      this.model.image = file
      this.posterPreview = URL.createObjectURL(file)
    },
    closeTag(tag) {
      this.model.tags.splice(this.model.tags.indexOf(tag), 1)
    },
    showTagInput() {
      this.tagInputVisible = true
      this.$nextTick(() => {
        this.$refs.taginput.focus()
      })
    },
    tagInputConfirm() {
      if (this.tagInputValue) {
        this.model.tags.push(this.tagInputValue)
      }
      this.tagInputVisible = false
      this.tagInputValue = ''
    },
  },
}
</script>
<style lang="scss" scoped>
  .tag-input {
    width: 100px;
  }
  .poster-preview {
    position: relative;

    img {
      width: 100%
    }
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
</style>
