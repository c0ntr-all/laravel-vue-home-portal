<template>
  <h2>Управление тегами</h2>
  <el-form
    ref="tagFormRef"
    :model="tagAdd.model"
    :rules="tagAdd.rules"
    label-width="120px"
    @submit.prevent="tagAddRequest(this.$refs.tagFormRef)"
  >
    <el-row class="mb-4" :gutter="10">
      <el-col :span="4">
        <el-form-item label-width="0" prop="tag">
          <el-input v-model="tagAdd.model.tagNew" type="text" placeholder="Введите тег!" />
        </el-form-item>
      </el-col>
      <el-col :span="3">
        <el-form-item label-width="0" prop="common">
          <el-checkbox v-model="tagAdd.model.common" label="Основной" border />
        </el-form-item>
      </el-col>
      <el-col :span="2">
        <el-button type="primary" @click="tagAddRequest(this.$refs.tagFormRef)">Добавить</el-button>
      </el-col>
    </el-row>
  </el-form>

  <h3>Основные теги</h3>
  <el-table
    :data="tags.common"
    row-key="id"
    :tree-props="{children: 'children'}"
    style="width: 100%"
    highlight-current-row
  >
    <el-table-column prop="label" label="Имя" width="500" sortable>
      <template #default="scope">
        <span class="el-table__placeholder" v-if="!scope.row.children && scope.row.parent_id === 0"></span>
        {{ scope.row.label }}
      </template>
    </el-table-column>
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
      <el-form-item label-width="0" prop="tag">
      <el-input
        v-model="this.tagEdit.model.tag.label"
        maxlength="40"
        placeholder="Введите имя тега"
        show-word-limit
        type="text"
      />
      </el-form-item>
      <el-form-item label-width="0" prop="tag">
        <el-checkbox v-model="tagEdit.model.tag.common" label="Основной" border />
      </el-form-item>
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
            maxlength="40"
            placeholder="Введите имя тега"
            show-word-limit
            type="text"
          />
        </el-form-item>
        <el-form-item label-width="0" prop="tag">
          <el-checkbox v-model="tagAdd.model.common" label="Основной" border />
        </el-form-item>
      </el-form>
    </template>
    <template #footer>
      <el-button type="primary" @click="tagAddRequest(this.$refs.tagNewFormRef, true)" round>Отправить</el-button>
    </template>
  </app-modal>

  <h3>Второстепенные теги</h3>
  <el-table
    :data="tags.secondary"
    row-key="id"
    style="width: 100%"
    highlight-current-row
  >
    <el-table-column prop="label" label="Имя" width="500" sortable>
      <template #default="scope">
        <span class="el-table__placeholder" v-if="!scope.row.children && scope.row.parent_id === 0"></span>
        {{ scope.row.label }}
      </template>
    </el-table-column>
    <el-table-column prop="createdAt" label="Дата добавления" width="250" sortable />
    <el-table-column label="Действия" width="350">
      <template #default="scope">
        <el-button size="small" @click="openTagEditModal(scope.row)">Редактировать</el-button>
      </template>
    </el-table-column>
  </el-table>
</template>
<script>
  import AppModal from "../../../default/AppModal";
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
            common: true,
            tagNew: '',
            tagNewChild: ''
          },
          modal: false
        },
        tagEdit: {
          model: {
            tag: '',
            common: true
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
          parent_id: isChild ? this.tagAdd.model.tag.id : 0,
          common: this.tagAdd.model.common,
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
          name: this.tagEdit.model.tag.label,
          common: this.tagEdit.model.tag.common,
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
