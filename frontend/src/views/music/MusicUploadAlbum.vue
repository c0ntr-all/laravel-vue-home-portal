<template>
  <el-row :gutter="10">
    <el-col :xs="24" :sm="12" :md="10" :lg="8" :xl="5">
      <h3>Загрузка Альбома</h3>
      <el-form :label-position="'right'">
        <el-form-item label="Название альбома" prop="title">
          <el-input
            v-model="this.model.album.title"
            maxlength="100"
            placeholder="Введите название"
            show-word-limit
            type="text"
          />
        </el-form-item>
        <el-form-item label="Описание альбома" prop="desc">
          <el-input type="textarea" placeholder="Описание альбома..." v-model="this.model.album.content" maxlength="10000" show-word-limit />
        </el-form-item>
        <el-form-item>
          <el-upload
            class="poster-uploader"
            action="#"
            :auto-upload="false"
            :show-file-list="false"
          >
            <img v-if="this.model.artist.poster.length" :src="this.model.artist.poster" class="poster" />
            <el-icon class="poster-uploader-icon"><plus /></el-icon>
          </el-upload>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="createAlbum">Создать</el-button>
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
        model: {
          album: {
            title: '',
            poster: []
          }
        }
      }
    },
    methods: {
      createAlbum() {

      },
      removePoster() {

      },
      beforePosterUpload() {

      },
      handlePosterSuccess() {

      },
      exceedPoster() {

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
</style>
