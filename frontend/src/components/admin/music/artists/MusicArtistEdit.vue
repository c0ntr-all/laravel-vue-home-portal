<template>
  <div class="mb-3">
    Всего исполнителей: <b>{{ total }}</b>
  </div>
  <div class="mb-3">
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
  <div class="mb-3" v-loading="loading">
    <el-table class="artists-list" :data="artists" style="width: 100%" highlight-current-row>
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
  </div>

  <el-pagination
    class="artists-pagination"
    :hide-on-single-page="true"
    :total="total"
    :page-size="pagination.per_page"
    layout="prev, pager, next"
    @current-change="loadArtists"
    background
  />

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
            multiple
            filterable
            placeholder="Tags"
            style="width: 100%"
          >
            <el-option
              v-for="item in tags.common"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Доп. теги">
          <el-select
            v-model="artistUpdate.model.secondaryTags"
            multiple
            filterable
            placeholder="Tags"
            style="width: 100%"
          >
            <el-option
              v-for="item in tags.secondary"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </el-form>
    </template>
    <template v-slot:footer>
      <el-button type="primary" @click="loadUpdateArtist">Сохранить</el-button>
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
        loading: false,
        tableData: [],
        artists: [],
        pagination: {},
        total: 0,
        tags: {
          common: {},
          secondary: {}
        },
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
        'secondaryTags'
      ]),
    },
    methods: {
      ...mapActions('artists', [
        'getArtists',
        'updateArtist',
        'searchArtists'
      ]),
      ...mapActions('tags', [
        'getTagsSelect',
      ]),

      searchRequest() {
        this.searchArtists(this.search).then(artists => {
          this.artists = artists
        }).catch(error => {
          this.$message.error(error);
        })
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
      loadUpdateArtist() {
        const formData = new FormData();
        const model = this.artistUpdate.model;

        for(let key in model) {
          if (!empty(model[key])) {
            formData.append(key, model[key])
          }
        }

        this.updateArtist(formData).then(data => {
          for(let key in this.artists) {
            if(this.artists[key].id === data.id) {
              this.artists[key] = data
            }
          }
          this.$message.success("Исполнитель успешно обновлён!");
        }).catch(error => {
          this.$message.error(error);
        })
      },
      loadArtists(page) {
        this.loading = true

        this.getArtists(page).then(data => {
          this.artists = data.artists
          this.pagination = data.pagination
          this.total = data.total

          this.loading = false
        }).catch(error => {
          this.$message.error(error)
          this.loading = false
        })
      },
      loadTagsSelect() {
        this.getTagsSelect().then(response => {
          this.tags.common = response.tags.common
          this.tags.secondary = response.tags.secondary
        }).catch(error => {
          this.$message.error(error)
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
  .artists-pagination {
    display: flex;
    justify-content: center;
  }
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
  .upload-folder {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .search-input {
    width: 379px;
  }
</style>
