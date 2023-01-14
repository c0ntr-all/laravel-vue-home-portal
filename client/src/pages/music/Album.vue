<template>
  <q-page class="q-pa-lg">
    <q-btn type="primary" @click="this.$router.push('/music/artists/' + album.artist.id)">Вернуться к исполнителю</q-btn>
    <div class="album-head">
      <div class="album-head__left">
        <div class="album-head__image">
          <a :href="album.image" @click.prevent="showImage = true">
            <img :src="album.image" alt="">
          </a>
        </div>
        <q-dialog v-model="showImage">
          <img :src="album.image" alt="">
        </q-dialog>
      </div>
      <div class="album-head__right">
        <h2 class="album-head__name">{{ album.name }}</h2>
        <div class="album-head__description">
          <p class="album-head__description-item album-artist">{{ album.artist?.name }}</p>
          <p class="album-head__description-item album-year">{{ album.year }}</p>
          <div class="album-head__description-item album-content">
            {{ album.content }}
          </div>
        </div>
<!--        <div class="album-head__tags">-->
<!--          <div class="tags-list">-->
<!--            <el-tag v-for="tag in album.tags" class="mx-1">{{ tag }}</el-tag>-->
<!--          </div>-->
<!--        </div>-->
      </div>
    </div>
    <div class="album-body">
      <div class="album-tracks" v-if="album.tracks">
        <div class="album-tracks__list">
          <q-table
            title="Треки"
            :rows="album.tracks"
            :columns="columns"
            row-key="name"
            :flat="true"
            :rows-per-page-options="[0]"
            :pagination.sync="{page: 1, rowsPerPage: 0}"
          />
<!--          <music-track-card v-for="track in album.tracks" :track="track" :key="track.id"></music-track-card>-->
        </div>
      </div>
    </div>
  </q-page>
  <!-- v-if="loading === false" чтобы компонент дождался загрузки основного альбома -->
<!--  <music-related-albums v-if="loading === false" :artistId="album.artist.id" :albumId="parseInt(albumId)" />-->
</template>
<script>
import {ref} from 'vue'
import API from "src/utils/api";

export default {
  setup() {
    const showImage = ref(false)
    const album = ref({})
    const columns = ref([{
      name: "number",
      required: true,
      label: '#',
      align: 'left',
      field: row => row.number,
      sortable: true
    },{
      name: "name",
      required: true,
      label: 'Имя',
      align: 'left',
      field: row => row.name,
      sortable: true
    },{
      name: "duration",
      required: true,
      label: 'Длительность',
      align: 'left',
      field: row => row.duration,
      sortable: true
    }])
    const getAlbum = async (id) => {
      const {data} = await API.post('music/albums', {id: id})

      album.value = data.data
    }

    return {
      showImage,
      album,
      columns,
      getAlbum
    }
  },
  props: {
    'id': String
  },
  // methods: {
  // created() {
  //   this.$watch(() => this.$route.params, (toParams, previousParams) => {
  //       // Так можно имитировать полную перезагрузку с выполнением всех запросов, в том числе related
  //       // this.loading = true
  //       this.loadAlbum(toParams.albumId)
  //     }
  //   )
  // },
  mounted() {
    this.getAlbum(this.id);
  }
}
</script>

<style lang="scss" scoped>
.album-head {
  display: flex;
  column-gap: 1rem;
  padding: 1rem 0 0 0;

  &__image {
    img {
      width: 200px;
      height: 200px;
    }
  }

  &__name {
    margin: 0 0 1rem 0;
    font-size: 45px;
    line-height: 45px;
    font-weight: 700;
  }

  &__description {
    margin: 0 0 1rem 0;

    &-item {
      margin-bottom: 1rem;
    }
  }
}
.album-body {
  display: flex;
  flex-direction: row;
}
.album-tracks {
  flex: 0 0 760px;

  &__header {
    display: flex;
    align-items: center;
    position: relative;
    height: auto;
    min-height: 45px;
    border-bottom: 1px solid #d7d7d7;

    &-number {
      flex: 0 0 40px;
      text-align: center;
    }
    &-name {
      flex: 1 1 100%;
    }
    &-favorite {
      display: flex;
      flex: 1 0 45px;
      justify-content: center;
      margin-right: 15px;
    }
  }
  &__list {
    display: flex;
    flex-direction: column;
    max-width: 760px;
  }
}
.album-artist {

}
.album-year {
  color: #777;
}
</style>
