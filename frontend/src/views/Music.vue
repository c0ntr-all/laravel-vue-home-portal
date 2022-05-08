<template>
  <el-header><h2 class="page-header">Музыка</h2></el-header>
  <el-main>
    <div class="tags">
      <h3>Genres</h3>
      <div class="tags-list">
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
    <div class="bands">
      <h3>Artists</h3>
      <div class="bands-list">
        <div class="band-item" v-for="band in filteredBands">
          {{ band.name }}
        </div>
      </div>
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
 }
  .tag-link {
    display: block;
    text-decoration: none;
  }
</style>
