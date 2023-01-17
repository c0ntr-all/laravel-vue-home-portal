<template>
  <div class="q-mb-md">
    Всего исполнителей: <b>{{ total }}</b>
  </div>
  <div class="artists-search q-mb-md">
    <q-input outlined bottom-slots v-model="search" label="Поиск" counter maxlength="12" dense>
      <template v-slot:append>
        <q-icon v-if="search !== ''" name="close" @click="search = ''" class="cursor-pointer" />
      </template>
      <template v-slot:after>
        <q-icon name="search" @click="searchArtists(search)" />
      </template>
    </q-input>
  </div>
  <div class="artists-list">
    <q-table
      :rows="artists"
      :columns="columns"
      row-key="name"
      :flat="true"
      :rows-per-page-options="[0]"
      :pagination.sync="{page: 1, rowsPerPage: 0}"
    >
      <template v-slot:body-cell-image="props">
        <q-td :props="props">
          <div class="artist-row__image">
            <img :src="props.value" :alt="props.value">
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
<!--  <div class="mb-3" v-loading="loading">-->
<!--    <el-table class="artists-list" :data="artists" style="width: 100%" highlight-current-row>-->
<!--      <el-table-column prop="id" label="Id" width="70" sortable />-->
<!--      <el-table-column prop="image" label="Изображение" width="150">-->
<!--        <template #default="scope">-->
<!--          <div class="artist-row__image">-->
<!--            <img :src="scope.row.image" alt="">-->
<!--          </div>-->
<!--        </template>-->
<!--      </el-table-column>-->
<!--      <el-table-column prop="name" label="Name" width="400" sortable />-->
<!--      <el-table-column prop="tags" label="Теги" width="450">-->
<!--        <template #default="props">-->
<!--          <div class="artist__tags"><span v-for="tag in props.row.tagsNames.common" class="artist-row__tag">{{ tag }}</span></div>-->
<!--          <div class="artist__tags"><span v-for="tag in props.row.tagsNames.secondary" class="artist-row__tag">{{ tag }}</span></div>-->
<!--        </template>-->
<!--      </el-table-column>-->
<!--      <el-table-column prop="createdAt" label="Дата добавления" width="250" sortable />-->
<!--      <el-table-column label="Действия" width="250">-->
<!--        <template #default="scope">-->
<!--          <el-button size="small" @click="openArtistUpdateModal(scope.row)">Редактировать</el-button>-->
<!--          <el-button size="small" type="danger">Удалить</el-button>-->
<!--        </template>-->
<!--      </el-table-column>-->
<!--    </el-table>-->
<!--  </div>-->

<!--  <el-pagination-->
<!--    class="artists-pagination"-->
<!--    :hide-on-single-page="true"-->
<!--    :total="total"-->
<!--    :page-size="pagination.per_page"-->
<!--    layout="prev, pager, next"-->
<!--    @current-change="loadArtists"-->
<!--    background-->
<!--  />-->
</template>
<script>
import {ref} from 'vue'
import API from "src/utils/api";

export default {
  setup() {
    const total = ref(0)
    const search = ref('')
    const columns = ref([{
      name: "id",
      required: true,
      label: 'ID',
      align: 'left',
      field: row => row.id,
      sortable: true,
      style: 'width: 40px'
    },{
      name: "image",
      required: true,
      label: 'Изображение',
      align: 'left',
      field: row => row.image,
      sortable: false,
      style: 'width: 60px'
    },{
      name: "name",
      required: true,
      label: 'Имя',
      align: 'left',
      field: row => row.name,
      sortable: true
    },{
      name: "tags",
      required: true,
      label: 'Теги',
      align: 'center',
      field: row => row.tags,
      sortable: false,
    }, {
      name: "createdAt",
      required: true,
      label: 'Дата добавления',
      align: 'left',
      field: row => row.createdAt,
      sortable: true
    }])
    const artists = ref([])
    const searchArtists = async (search) => {
      const {data} = await API.post('music/admin/artists/search', {name: search})
      artists.value = data.data.artists
    }
    const getArtists = async (page) => {
      const {data} = await API.post('music/admin/artists/get', {page: page})
      artists.value = data.data.artists
      total.value = data.data.total
    }

    return {
      total,
      search,
      columns,
      artists,
      getArtists,
      searchArtists
    }
  },
  mounted() {
    this.getArtists()
  }
}
</script>
<style lang="scss" scoped>
.artists-search {
  max-width: 400px;
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
</style>
