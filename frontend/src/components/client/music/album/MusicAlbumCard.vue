<template>
  <el-card class="album-card" :body-style="{ padding: '0px' }">
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
</template>
<script>
  export default {
    props: {
      album: Object
    }
  }
</script>
<style lang="scss">
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
