<template>
  <div class="music">
    <div class="music__page">
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
      <div class="music__filter">
        <music-artists-filter></music-artists-filter>
      </div>
      <div class="artists">
        <h3>Artists</h3>
        <el-space alignment="flex-start" wrap>
          <el-card class="artist-card" :body-style="{ padding: '0px' }" v-for="artist in filteredBands" :key="artist.id">
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
        </el-space>
      </div>
    </div>
  </div>
</template>
<script>
  import MusicArtistsFilter from '../../components/music/page/MusicArtistsFilter'

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
        this.$store.dispatch('loadTags')
        this.$store.dispatch('loadArtists')
      }
    },
    computed: {
      filteredBands() {
        return this.$store.getters.music.artists.filter(elem => {
          if(this.activeTag === '') {
            return true
          }else{
            return elem.tagsNames.indexOf(this.activeTag) > -1
          }
        })
      }
    },
    components: {
      MusicArtistsFilter
    },
    mounted() {
      this.loadData();
    }
  }
</script>

<style lang="scss" scoped>
  h3 {
    margin-top: 0;
  }
  .music {
    display: flex;
    flex-direction: row;

    &__page {

    }
  }
  .tags-list {
    display: flex;
    flex-wrap: wrap;
    column-gap: 1rem;
    row-gap: 1rem;
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
