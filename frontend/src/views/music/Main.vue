<template>
  <div class="tags">
    <h3>Genres</h3>
    <div class="tags-list">
      <a href="" class="tag-link" @click.prevent="this.activeTag = ''">
        <el-tag class="mx-1" effect="light">All</el-tag>
      </a>
      <a href="" class="tag-link" @click.prevent="this.activeTag = tag.label" v-for="tag in this.$store.getters.music.tags">
        <el-tag
          :key="tag.label"
          :type="tag.type"
          class="mx-1"
          effect="dark"
        >
          {{ tag.label }}
        </el-tag>
      </a>
    </div>
  </div>
  <div class="artists">
    <h3>Artists</h3>
    <el-space alignment="flex-start" wrap>
      <el-card class="artist-card" :body-style="{ padding: '0px' }" v-for="band in filteredBands" :key="band.id">
        <div class="artist-card__image">
          <img
            src="https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png"
            class="image"
          />
        </div>
        <div style="padding: 14px">
          <router-link :to="'/music/artists/' + band.id"><span>{{ band.name }}</span></router-link>
          <div class="artist-card__footer">
            <div class="tags-list">
              <el-tag v-for="tag in band.tags" class="mx-1">{{ tag }}</el-tag>
            </div>
          </div>
        </div>
      </el-card>
    </el-space>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        loading: false,
        activeTag: '',
        model: {
        }
      }
    },
    methods: {
      loadData() {

      }
    },
    computed: {
      filteredBands() {
        return this.$store.getters.music.bands.filter(elem => {
          if(this.activeTag === '') {
            return true
          }else{
            return elem.tags.indexOf(this.activeTag) > -1
          }
        })
      }
    },
    mounted() {
      console.log(this.$store.getters.music.tags)
      this.loadData();
    }
  }
</script>

<style lang="scss" scoped>
  h3 {
    margin-top: 0;
  }
  .tags-list {
    display: flex;
    column-gap: 1rem;
    margin-bottom: 1rem;

    .artist-card & {
      column-gap: .250rem;
      margin-bottom: 0;
    }
  }
  .tag-link {
    display: block;
    text-decoration: none;
  }

  .artist-card {
    background-color: #ebecf0;
    border-radius: 3px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    max-height: 100%;
    position: relative;
    white-space: normal;
    width: 237px;

    &__footer {
      margin-top: 13px;
      line-height: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    &__image {

      img {
        max-height: 150px;
        width: 100%;
        object-fit: cover;
      }
    }
  }
</style>
