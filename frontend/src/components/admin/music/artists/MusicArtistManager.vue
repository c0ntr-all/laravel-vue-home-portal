<template>
  <div class="mt-4">
    <el-input
      v-model="search"
      placeholder="Введите имя исполнителя"
      class="search-input"
    >
      <template #append>
        <el-button :icon="Search" @click="searchRequest()" />
      </template>
    </el-input>
  </div>
  <el-table
    :data="artists"
    style="width: 100%"
    highlight-current-row
  >
    <el-table-column prop="id" label="Id" width="70" sortable />
    <el-table-column prop="image" label="Изображение" width="150">
      <template #default="scope">
        <div class="artist-row__image">
          <img :src="scope.row.image" alt="">
        </div>
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Name" width="400" sortable />
    <el-table-column prop="tags" label="Теги" width="450">
      <template #default="props">
        <div class="artist__tags"><span v-for="tag in props.row.tagsNames.common" class="artist-row__tag">{{ tag }}</span></div>
        <div class="artist__tags"><span v-for="tag in props.row.tagsNames.secondary" class="artist-row__tag">{{ tag }}</span></div>
      </template>
    </el-table-column>
    <el-table-column prop="createdAt" label="Дата добавления" width="250" sortable />
    <el-table-column label="Действия" width="250">
      <template #default="scope">
        <el-button size="small" @click="openArtistUpdateModal(scope.row)">Редактировать</el-button>
        <el-button size="small" type="danger">Удалить</el-button>
      </template>
    </el-table-column>
  </el-table>
  <app-modal :openModal="artistUpdate.modal" @closeModal="closeArtistUpdateModal">
    <template v-slot:title>
      <h3>Редактирование Исполнителя</h3>
    </template>
    <template v-slot:content>
      <el-form>
        <el-form-item label="Название исполнителя" prop="name">
          <el-input
            v-model="artistUpdate.model.name"
            maxlength="100"
            placeholder="Введите название"
            show-word-limit
            type="text"
          />
        </el-form-item>
        <el-form-item label="Описание исполнителя" prop="content">
          <el-input
            type="textarea"
            placeholder="Описание исполнителя..."
            v-model="artistUpdate.model.content"
            maxlength="10000" show-word-limit
          />
        </el-form-item>
        <el-form-item label="Постер">
          <div class="input-poster">
            <input type="file" id="poster" ref="poster" @change="onChangePoster"/>
          </div>
          <div class="poster-preview">
            <img v-if="artistUpdate.posterPreview" :src="artistUpdate.posterPreview" alt="">
          </div>
        </el-form-item>
        <el-form-item label="Основные теги">
          <el-select
            v-model="artistUpdate.model.commonTags"
            :loading="this.tagsLoading"
            multiple
            filterable
            placeholder="Tags"
            style="width: 100%"
          >
            <el-option
              v-for="item in this.commonTags"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Доп. теги">
          <el-select
            v-model="artistUpdate.model.secondaryTags"
            :loading="this.tagsLoading"
            multiple
            filterable
            placeholder="Tags"
            style="width: 100%"
          >
            <el-option
              v-for="item in this.secondaryTags"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </el-form>
    </template>
    <template v-slot:footer>
      <el-button type="primary" @click="editArtistRequest">Сохранить</el-button>
    </template>
  </app-modal>
</template>
<script setup>
  import {
    Search
  } from '@element-plus/icons-vue'
</script>
<script>
  import empty from "../../../../utils/empty"

  import {mapActions, mapGetters} from 'vuex'

  import AppModal from "../../../default/AppModal"

  export default {
    data() {
      return {
        tableData: [],
        artistUpdate: {
          posterPreview: null,
          tagInputVisible: false,
          tagInputValue: '',
          model: {
            image: '',
            name: '',
            content: '',
            commonTags: '',
            secondaryTags: '',
          },
          modal: false
        },
        search: ''
      }
    },
    computed: {
      ...mapGetters('artists', [
        'commonTags',
        'secondaryTags',
        'tagsLoading',
        'artists'
      ]),
    },
    methods: {
      ...mapActions('artists', [
        'loadTagsSelect',
        'switchTagsLoading',
        'loadArtists',
        'updateArtist'
      ]),

      searchRequest() {

      },

      openArtistUpdateModal(item) {
        //Подгрузка при открытии модального окна, чтобы select успел соотнести id'шники c name'ами тегов
        this.loadTagsSelect()
        this.artistUpdate.modal = true

        this.artistUpdate.model.id = item.id
        this.artistUpdate.model.name = item.name
        this.artistUpdate.model.content = item.content
        this.artistUpdate.model.commonTags = item.tags['common']
        this.artistUpdate.model.secondaryTags = item.tags['secondary']
      },
      closeArtistUpdateModal() {
        this.artistUpdate.modal = false
        this.artistUpdate.model = {}
        this.artistUpdate.posterPreview = null
        this.artistUpdate.tagInputValue = ''
      },
      onChangePoster($event) {
        const file = event.target.files[0]
        this.artistUpdate.model.image = file
        this.artistUpdate.posterPreview = URL.createObjectURL(file)
      },
      closeTag(tag) {
        this.artistUpdate.model.tags.splice(this.artistUpdate.model.tags.indexOf(tag), 1)
      },
      showTagInput() {
        this.artistUpdate.tagInputVisible = true
        this.$nextTick(() => {
          this.$refs.taginput.focus()
        })
      },
      tagInputConfirm() {
        if (this.artistUpdate.tagInputValue) {
          this.artistUpdate.model.tags.push(this.artistUpdate.tagInputValue)
        }
        this.artistUpdate.tagInputVisible = false
        this.artistUpdate.tagInputValue = ''
      },
      editArtistRequest() {
        const formData = new FormData();
        const model = this.artistUpdate.model;

        for(let key in model) {
          if (!empty(model[key])) {
            formData.append(key, model[key])
          }
        }

        this.updateArtist(formData).then(result => {
          this.$message.success("Исполнитель успешно обновлён!");
        }).catch(error => {
          this.$message.error(error);
        })
      },
    },
    mounted() {
      this.loadArtists();
    },
    components: {
      AppModal
    },
  }
</script>
<style lang="scss" scoped>
  .artist-row {
    &__image {
      width: 50px;
      height: 50px;

      img {
        width: 100%;
        object-fit: cover;
      }
    }
    &__tag:not(:last-child) {
      &::after {
        content: ', '
      }
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
  .search-input {
    width: 379px;
    margin: 0 0 15px;
  }
</style>
