<template>
  <el-card class="artist-card" :body-style="{ padding: '0px' }">
    <div class="artist-card__image">
      <img :src="artist.image" class="image"/>
    </div>
    <div style="padding: 14px">
      <router-link :to="'/music/artists/' + artist.id"><span>{{ artist.name }}</span></router-link>
      <div class="artist-card__footer">
        <div class="tags-list">
          <el-tag v-for="tag in artist.tagsNames" class="mx-1">{{ tag }}</el-tag>
        </div>
      </div>
    </div>
  </el-card>
</template>
<script>
  export default {
    props: {
      artist: Object
    }
  }
</script>
<style lang="scss" scoped>
  .artist-card {
    background-color: #ebecf0;
    border-radius: 3px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    max-height: 100%;
    position: relative;
    white-space: normal;
    width: 250px;

    .tags-list {
      column-gap: .250rem;
      margin-bottom: 0;
    }

    &__footer {
      margin-top: 13px;
      line-height: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    &__image {

      img {
        max-height: 250px;
        width: 100%;
        object-fit: cover;
      }
    }
  }
</style>
