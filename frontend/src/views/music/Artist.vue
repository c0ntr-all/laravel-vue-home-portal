<template>
  <div class="artist">
    <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться назад</el-button>
    <el-skeleton :loading="loading" animated>
      <template #template>
        <div class="artist-head">
          <div class="artist-head__left">
            <div class="artist-head__image">
              <el-skeleton-item variant="image" style="width: 200px; height: 200px"/>
            </div>
          </div>
          <div class="artist-head__right">
            <el-skeleton-item variant="text" style="width: 500px; height: 50px; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 80%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 90%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 100%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 60%; margin: 10px 0" />
          </div>
        </div>
      </template>
      <template #default>
        <div class="artist-head">
          <div class="artist-head__left">
            <div class="artist-head__image">
              <a :href="this.artist.image"><img :src="this.artist.image" alt=""></a>
            </div>
          </div>
          <div class="artist-head__right">
            <h2 class="artist-head__name">{{ artist.name }}</h2>
            <div class="artist-head__description">
              {{ artist.content }}
            </div>
            <div class="artist-head__tags">
              <div class="tags-list">
                <el-tag v-for="tag in artist.tagsNames.common" class="mx-1">{{ tag }}</el-tag>
                <el-tag v-for="tag in artist.tagsNames.secondary" class="mx-1">{{ tag }}</el-tag>
              </div>
            </div>
          </div>
        </div>
        <div class="artist-albums" v-if="this.artist.albums">
          <h3>Альбомы</h3>
          <el-space alignment="flex-start" wrap>
            <el-card class="album-card" :body-style="{ padding: '0px' }" v-for="album in this.artist.albums" :key="album.id">
              <div class="album-card__image" v-if="album.image">
                <img :src="'http://home-portal.local/storage/' + album.image" alt="">
              </div>
              <div class="album-card__image" v-else>
                <svg class="bd-placeholder-img card-img-top" width="100%" height="150" xmlns="http://www.w3.org/2000/svg" role="img" focusable="false">
                  <title>Placeholder</title>
                  <rect width="100%" height="100%" fill="#868e96"></rect>
                  <text x="32%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text>
                </svg>
              </div>
              <div class="album-card__info">
                <router-link class="album-card__link" :to="'/music/albums/' + album.id">
                  <p class="album-card__title" :title="album.name">{{ album.name }}</p>
                </router-link>
                <p class="album-card__year">{{ album.year }}</p>
              </div>
            </el-card>
          </el-space>
        </div>
      </template>
    </el-skeleton>
  </div>
</template>
<script setup>
  import {
    ArrowLeft,
  } from '@element-plus/icons-vue'
</script>
<script>
  import {mapActions} from "vuex"

  export default {
    data() {
      return {
        loading: true,
        artist: {}
      }
    },
    props: {
      'artistId': String
    },
    methods: {
      ...mapActions('artists', ['getArtist']),
      //todo Сделать универсальные метод т.к. такие методы будут повторяться на каждой странице
      loadArtist() {
        this.getArtist(this.artistId).then(result => {
          this.artist = result
          this.loading = false
        }).catch(error => {
          this.$message.error(error)
          this.loading = false
        })
      }
    },
    mounted() {
      this.loadArtist()
    }
  }
</script>

<style lang="scss" scoped>
  .artist-head {
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
    }
  }
  .album-card {
    max-width: 200px;

    &__image {
      margin-bottom: 5px;

      img, svg {
        width: 200px;
        height: 200px;
      }
    }
    &__info {
      padding: 0 8px;
    }
    &__link {
      color: #222;
      text-decoration: none;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;

      &:hover {
        color: #409eff;
      }
    }
    &__title {
      margin-bottom: 5px;
      font-size: 14px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    &__year {
      margin-bottom: 5px;
      font-size: 14px;
      color: #777;
    }
  }
</style>
