<template>
  <h3>Управление тегами</h3>
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
  <app-modal :openModal="openEditModal" @closeModal="openEditModal = false">
    <template v-slot:title>
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
      console.log(this.$store.getters.music.tags)
    },
    components: {
      AppModal
    },
  }
</script>
<style lang="scss">

</style>
