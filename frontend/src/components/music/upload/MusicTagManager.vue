<template>
  <h3>Управление тегами</h3>
  <el-form
    ref="tagFormRef"
    :model="tagAdd.model"
    :rules="tagAdd.rules"
    label-width="120px"
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
      <template #default>
        <el-button size="small">Редактировать</el-button>
      </template>
    </el-table-column>
  </el-table>
</template>
<script>
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
        }
      }
    },
    methods: {
      async submitForm(formEl) {
        if (!formEl) return

        await formEl.validate((valid, fields) => {
          if (valid) {
            this.$store.dispatch('addTag', this.tagAdd.model.tag)
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
  }
</script>
<style lang="scss">

</style>
