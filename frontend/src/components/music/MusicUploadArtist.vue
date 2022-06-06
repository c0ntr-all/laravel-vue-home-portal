<template>
  <el-row :gutter="10">
    <el-col :xs="24" :sm="12" :md="10" :lg="8" :xl="5">
      <h3>Загрузка Банды</h3>
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
        <el-form-item label="Описание банды" prop="desc">
          <el-input type="textarea" placeholder="Описание банды..." v-model="this.model.artist.description" maxlength="10000" show-word-limit />
        </el-form-item>
        <el-form-item>
          <div class="input-poster">
            <input type="file" id="poster" ref="poster" @change="onChangePoster"/>
          </div>
          <div class="poster-preview">
            <img v-if="posterPreview" :src="posterPreview" alt="">
          </div>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="createArtist">Создать</el-button>
        </el-form-item>
      </el-form>
    </el-col>
  </el-row>
</template>
<script setup>
  import {
    Plus
  } from '@element-plus/icons-vue'
</script>
<script>
  import API from "../../utils/api";

  export default {
    data() {
      return {
        posterPreview: null,
        model: {
          artist: {
            name: '',
            description: '',
            image: ''
          }
        }
      }
    },
    methods: {
      async createArtist(e) {
        const formData = new FormData();
        formData.append('name', this.model.artist.name)
        formData.append('description', this.model.artist.description)
        formData.append('image', this.model.artist.image)

        this.$store.dispatch('createMusicArtist', {
          data: formData,
        }).then(result => {
          this.$message.success("Артист успешно добавлен!");
        }).catch(error => {
          this.$message.error(error);
        })

        this.model.artist = ''
      },
      onChangePoster($event) {
        const file = event.target.files[0]
        this.model.artist.image = file
        this.posterPreview = URL.createObjectURL(file)
      },
      loadData() {

      }
    },
    mounted() {
      this.loadData();
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
</style>
