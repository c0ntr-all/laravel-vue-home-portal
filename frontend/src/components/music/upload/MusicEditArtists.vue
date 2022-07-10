<template>
  <el-table
    :data="this.$store.getters.music.artists"
    style="width: 100%"
    highlight-current-row
  >
    <el-table-column prop="id" label="Id" width="70" sortable />
    <el-table-column prop="image" label="Изображение" width="100">
      <template #default="scope">
        <div class="artist-row__image">
          <img :src="scope.row.image" alt="">
        </div>
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Name" width="200" sortable />
    <el-table-column prop="tags" label="Теги" width="400">
      <template #default="props">
        <span v-for="tag in props.row.tags" class="artist-row__tag">{{ tag }}</span>
      </template>
    </el-table-column>
    <el-table-column prop="createdAt" label="Дата добавления" width="250" sortable />
    <el-table-column label="Действия" width="250">
      <template #default>
        <el-button size="small">Редактировать</el-button>
        <el-button size="small" type="danger">Удалить</el-button>
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
