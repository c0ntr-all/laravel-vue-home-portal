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
    <el-table-column prop="tags" label="Теги" width="550">
      <template #default="props">
        <span v-for="tag in props.row.tags" class="artist-row__tag">{{ tag }}</span>
      </template>
    </el-table-column>
    <el-table-column prop="createdAt" label="Дата добавления" width="250" sortable />
    <el-table-column label="Действия" width="350">
      <template #default>
        <el-button size="small" @click="artistEdit.modal = true">Редактировать</el-button>
        <el-button size="small" type="danger">Удалить</el-button>
      </template>
    </el-table-column>
  </el-table>
  <app-modal :openModal="artistEdit.modal" @closeModal="artistEdit.modal = false">
    <template v-slot:title>
      <h3>Редактирование Исполнителя</h3>
    </template>
    <template v-slot:content>
      <el-form>
        <el-form-item label="Название банды" prop="name">
          <el-input
            v-model="artistEdit.model.name"
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
            v-model="artistEdit.model.content"
            maxlength="10000" show-word-limit
          />
        </el-form-item>
        <el-form-item label="Постер">
          <div class="input-poster">
            <input type="file" id="poster" ref="poster" @change="onChangePoster"/>
          </div>
          <div class="poster-preview">
            <img v-if="artistEdit.posterPreview" :src="artistEdit.posterPreview" alt="">
          </div>
        </el-form-item>
        <el-form-item label="Теги">
          <el-tag
            v-for="tag in artistEdit.model.tags"
            :key="tag"
            class="mx-1"
            closable
            :disable-transitions="false"
            @close="closeTag(tag)"
          >
            {{ tag }}
          </el-tag>
          <el-input
            v-if="artistEdit.tagInputVisible"
            ref="taginput"
            v-model="artistEdit.tagInputValue"
            class="ml-1 tag-input"
            size="small"
            @keyup.enter="tagInputConfirm"
            @blur="tagInputConfirm"
          />
          <el-button v-else class="button-new-tag ml-1" size="small" @click="showTagInput">
            + New Tag
          </el-button>
        </el-form-item>
      </el-form>
    </template>
    <template v-slot:footer>
      <el-button type="primary" @click="editArtistRequest">Сохранить</el-button>
    </template>
  </app-modal>
</template>
<script>
  import AppModal from "../../default/AppModal";

  export default {
    data() {
      return {
        tableData: [],
        artistEdit: {
          posterPreview: null,
          tagInputVisible: false,
          tagInputValue: '',
          model: {
            image: '',
            name: '',
            description: '',
            tags: []
          },
          modal: false
        },
      }
    },
    methods: {
      onChangePoster($event) {
        const file = event.target.files[0]
        this.artistEdit.model.image = file
        this.artistEdit.posterPreview = URL.createObjectURL(file)
      },
      closeTag(tag) {
        this.artistEdit.model.tags.splice(this.artistEdit.model.tags.indexOf(tag), 1)
      },
      showTagInput() {
        this.artistEdit.tagInputVisible = true
        this.$nextTick(() => {
          this.$refs.taginput.focus()
        })
      },
      tagInputConfirm() {
        if (this.artistEdit.tagInputValue) {
          this.artistEdit.model.tags.push(this.artistEdit.tagInputValue)
        }
        this.artistEdit.tagInputVisible = false
        this.artistEdit.tagInputValue = ''
      },
      editArtistRequest() {

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
</style>
