<template>
  <el-header><h2 class="page-header">Музыка</h2></el-header>
  <el-main>
    <div class="tags">
      <h3>Genres</h3>
      <div class="tags-list">
        <a href="" class="tag-link" @click.prevent="this.activeTag = ''">
          <el-tag class="mx-1" effect="light">All</el-tag>
        </a>
        <a href="" class="tag-link" @click.prevent="this.activeTag = tag.label" v-for="tag in tags">
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
        <el-card class="artist-card" :body-style="{ padding: '0px' }" v-for="band in filteredBands" :key="band.name">
          <div class="artist-card__image">
            <img
              src="https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png"
              class="image"
            />
          </div>
          <div style="padding: 14px">
            <span>{{ band.name }}</span>
            <div class="artist-card__footer">
              <div class="tags-list">
                <el-tag v-for="tag in band.tags" class="mx-1">{{ tag }}</el-tag>
              </div>
            </div>
          </div>
        </el-card>
      </el-space>
    </div>
  </el-main>
</template>
<script>
  import API from "../utils/api";

  export default {
    data() {
      return {
        loading: false,
        activeTag: '',
        tags: [
          {type: '', label: 'Metal'},
          {type: 'success', label: 'Rock'},
          {type: 'info', label: 'Break Beat'},
          {type: 'danger', label: 'Industrial'},
          {type: 'warning', label: 'Classic'},
        ],
        bands: [
          {name: 'Fear Factory', tags: ['Metal','Rock','Industrial']},
          {name: 'Metallica', tags: ['Metal','Rock']},
          {name: 'Soulfly', tags: ['Metal','Rock']},
          {name: 'Slipknot', tags: ['Metal','Rock']},
          {name: 'Cannibal Corpse', tags: ['Metal','Rock']},
          {name: 'Prodigy', tags: ['Break Beat','Industrial']},
          {name: 'Bach', tags: ['Classic']}
        ],
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
        return this.bands.filter(elem => {
          if(this.activeTag === '') {
            return true
          }else{
            return elem.tags.indexOf(this.activeTag) > -1
          }
        })
      }
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
