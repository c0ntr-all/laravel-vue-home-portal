<template>
  <el-table
    :data="this.$store.getters.music.artists"
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
        <span v-for="tag in props.row.tags" class="artist-row__tag">{{ tag }}</span>
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
        <el-form-item label="Название банды" prop="name">
          <el-input
            v-model="artistUpdate.model.name"
            maxlength="100"
            placeholder="Введите название"
            show-word-limit
            type="text"
          />
        </el-form-item>
        <el-form-item label="Описание банды" prop="content">
          <el-input
            type="textarea"
            placeholder="Описание банды..."
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
        <el-form-item label="Теги">
          <el-select
            v-model="artistUpdate.model.tags"
            :loading="this.tagsLoading"
            multiple
            filterable
            placeholder="Tags"
            style="width: 240px"
            @focus="loadTagsSelect"
          >
            <el-option
              v-for="item in tags"
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
<script>
  import empty from "../../../utils/empty"

  import {mapActions, mapGetters} from 'vuex'

  import AppModal from "../../default/AppModal";

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
            tags: ''
          },
          modal: false
        },
      }
    },
    methods: {
      ...mapActions(['loadTagsSelect']),
      ...mapGetters(['tags','tagsLoading']),

      openArtistUpdateModal(item) {
        this.artistUpdate.modal = true

        delete item.albums
        this.artistUpdate.model = item
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
            if(key === 'image' && typeof model[key] === 'string') continue

            formData.append(key, model[key])
          }
        }

        this.$store.dispatch('updateArtist', formData).then(result => {
          this.$message.success("Артист успешно обновлён!");

          for (let key in this.artistUpdate.model) {
            this.artistUpdate.model[key] = ''
            if(key === 'image') {
              this.$refs.poster.value = ''
            }
          }
          this.artistUpdate.posterPreview = null
        }).catch(error => {
          this.$message.error(error);
        })
      },
      loadData() {
        this.$store.dispatch('loadArtists')
      }
    },
    mounted() {
      this.loadData();
    },
    components: {
      AppModal
    },
  }
</script>
<style lang="scss">
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
</style>
