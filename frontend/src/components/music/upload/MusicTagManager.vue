<template>
  <h3>Управление тегами</h3>
  <el-form
    ref="tagFormRef"
    :model="tagAdd.model"
    :rules="tagAdd.rules"
    label-width="120px"
    @submit.prevent="submitForm(this.$refs.tagFormRef)"
  >
    <el-row class="mb-4">
      <el-col :span="4"><div class="grid-content ep-bg-purple" />
        <el-form-item label-width="0" prop="tag">
          <el-input v-model="tagAdd.model.tag" type="text" placeholder="Введите тег!" />
        </el-form-item>
      </el-col>
      <el-col :span="4"><div class="grid-content ep-bg-purple-light" />
        <el-button type="primary" @click="submitForm(this.$refs.tagFormRef)">Добавить</el-button>
      </el-col>
    </el-row>
  </el-form>
  <el-table
    :data="this.$store.getters.music.tags"
    style="width: 100%"
    highlight-current-row
  >
    <el-table-column prop="id" label="Id" width="70" sortable />
    <el-table-column prop="label" label="Имя" width="200" sortable />
    <el-table-column prop="createdAt" label="Дата добавления" width="250" sortable />
    <el-table-column label="Действия" width="250">
      <template #default="scope">
        <el-button size="small" @click="openTagEditModal(scope.row)">Редактировать</el-button>
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
      <el-button type="primary" @click="editTagRequest" round>Создать</el-button>
    </template>
  </app-modal>
</template>
<script>
  import AppModal from "../../default/AppModal";

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
            tag: ''
          }
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
      openTagEditModal(item) {
        this.tagEdit.modal = true
        this.tagEdit.model.tag = item
      },
      editTagRequest() {
        this.$store.dispatch('editTag', {
          id: this.tagEdit.model.tag.id,
          name: this.tagEdit.model.tag.label
        })
      },
      submitForm(formEl) {
        if (!formEl) return

        formEl.validate((valid, fields) => {
          if (valid) {
            this.$store.dispatch('addTag', this.tagAdd.model.tag)
            .then(result => {
              this.$message.success(`Тег ${result.label} успешно добавлен!`)
              this.tagAdd.model.tag = ''
            }).catch(error => {
              this.$message.error(error.message)
            })
          }
        })
      },
      loadData() {
        this.$store.dispatch('loadTags')
      }
    },
    mounted() {
      this.loadData();
    },
    components: {
      AppModal
    }
  }
</script>
<style lang="scss">

</style>
