<template>
  <h3>Управление тегами</h3>
  <el-row class="mb-4">
    <el-button type="primary" @click="openCreateModal = true">Добавить</el-button>
  </el-row>
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
        <el-button size="small" @click="openEditModal = true">Редактировать</el-button>
      </template>
    </el-table-column>
  </el-table>
  <app-modal :openModal="openEditModal" @closeModal="openEditModal = false">
    <template v-slot:title>
      Редактирование тега
    </template>
    <template v-slot:content>
    </template>
    <template v-slot:footer>
    </template>
  </app-modal>
  <app-modal :openModal="openCreateModal" @closeModal="openCreateModal = false">
    <template v-slot:title>
      Добавление тега
    </template>
    <template v-slot:content>
    </template>
    <template v-slot:footer>
    </template>
  </app-modal>
</template>
<script>
  import AppModal from "../../default/AppModal";

  export default {
    data() {
      return {
        openEditModal: false,
        openCreateModal: false,
        tableData: []
      }
    },
    methods: {
      loadData() {
        this.$store.dispatch('loadTags')
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

</style>
