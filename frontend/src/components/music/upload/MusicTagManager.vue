<template>
  <h3>Управление тегами</h3>
  <el-form
    ref="tagFormRef"
    :model="tagAdd.model"
    :rules="tagAdd.rules"
    label-width="120px"
    @submit.prevent="tagAddRequest(this.$refs.tagFormRef)"
  >
    <el-row class="mb-4">
      <el-col :span="4"><div class="grid-content ep-bg-purple" />
        <el-form-item label-width="0" prop="tag">
          <el-input v-model="tagAdd.model.tagNew" type="text" placeholder="Введите тег!" />
        </el-form-item>
      </el-col>
      <el-col :span="4"><div class="grid-content ep-bg-purple-light" />
        <el-button type="primary" @click="tagAddRequest(this.$refs.tagFormRef)">Добавить</el-button>
      </el-col>
    </el-row>
  </el-form>
  <el-table
    :data="tags.items"
    style="width: 100%"
    highlight-current-row
  >
    <el-table-column prop="id" label="Id" width="70" sortable />
    <el-table-column prop="label" label="Имя" width="200" sortable />
    <el-table-column prop="createdAt" label="Дата добавления" width="250" sortable />
    <el-table-column label="Действия" width="350">
      <template #default="scope">
        <el-button size="small" @click="openTagEditModal(scope.row)">Редактировать</el-button>
        <el-button size="small" @click="openTagAddModal(scope.row)">Добавить</el-button>
      </template>
    </el-table-column>
  </el-table>
  <app-modal :openModal="tagEdit.modal" @closeModal="tagEdit.modal = false">
    <template #title>
      <h3>Редактировать тег</h3>
    </template>
    <template #content>
      <el-input
        v-model="this.tagEdit.model.tag.label"
        maxlength="20"
        placeholder="Введите имя тега"
        show-word-limit
        type="text"
      />
    </template>
    <template #footer>
      <el-button type="primary" @click="tagEditRequest" round>Сохранить</el-button>
    </template>
  </app-modal>
  <app-modal :openModal="tagAdd.modal" @closeModal="tagAdd.modal = false">
    <template #title>
      <h3>Добавить дочерний тег для "{{ tagAdd.model.tag.label }}"</h3>
    </template>
    <template #content>
      <el-form
        ref="tagNewFormRef"
        :model="tagAdd.model"
        :rules="tagAdd.rules"
        label-width="120px"
        @submit.prevent="tagAddRequest(this.$refs.tagNewFormRef, true)"
      >
        <el-form-item label-width="0" prop="tag">
          <el-input
            v-model="this.tagAdd.model.tagNewChild"
            maxlength="20"
            placeholder="Введите имя тега"
            show-word-limit
            type="text"
          />
        </el-form-item>
      </el-form>
    </template>
    <template #footer>
      <el-button type="primary" @click="tagAddRequest(this.$refs.tagNewFormRef, true)" round>Отправить</el-button>
    </template>
  </app-modal>
</template>
<script>
  import AppModal from "../../default/AppModal";
  import { mapGetters, mapActions } from "vuex";

  export default {
    data() {
      return {
        tableData: [],
        tagAdd: {
          rules: {
            tag: [{
              required: true,
              message: 'Введите тег!',
              trigger: 'change',
            }]
          },
          model: {
            tag: {},
            tagNew: '',
            tagNewChild: ''
          },
          modal: false
        },
        tagEdit: {
          model: {
            tag: ''
          },
          modal: false
        },
      }
    },
    methods: {
      ...mapActions('music', ['loadTags','editTag','addTag']),

      openTagEditModal(item) {
        this.tagEdit.modal = true
        this.tagEdit.model.tag = item
      },
      openTagAddModal(item) {
        this.tagAdd.modal = true
        this.tagAdd.model.tag = item
      },
      tagAddRequest(formEl, isChild) {
        if (!formEl) return

        let tag = {
          tag: isChild ? this.tagAdd.model.tagNewChild : this.tagAdd.model.tagNew,
          parent_id: isChild ? this.tagAdd.model.tag.id : 0
        }

        formEl.validate((valid, fields) => {
          if (valid) {
            this.addTag(tag)
              .then(result => {
                this.$message.success(`Тег ${result.label} успешно добавлен!`)
                this.tagAdd.model.tagNew = ''
              }).catch(error => {
              this.$message.error(error.message)
            })
          }
        })
      },
      tagEditRequest() {
        this.editTag( {
          id: this.tagEdit.model.tag.id,
          name: this.tagEdit.model.tag.label
        })
      },
    },
    computed: {
      ...mapGetters('music', ['tags'])
    },
    mounted() {
      this.loadTags();
    },
    components: {
      AppModal
    }
  }
</script>
<style lang="scss">

</style>
