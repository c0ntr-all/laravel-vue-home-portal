<template>
  <div class="music-filter mb-3">
    <h3>Filter</h3>
    <blockquote class="tip danger">
      <p class="tip__title">Внимание!</p>
      <p>Дополнительный стиль временно не работает!</p>
    </blockquote>
    <div class="music-filter__params">
      <el-radio-group class="mb-2 mr-2" v-model="type" size="default" @change="checkRules()">
        <el-radio-button label="strict">Точное совпадение</el-radio-button>
        <el-radio-button label="hierarchical">Иерархический поиск</el-radio-button>
      </el-radio-group>
      <el-checkbox v-model="union" :disabled="type !== 'strict'" border>Совместный</el-checkbox>
    </div>
    <el-select
      v-model="styles.value"
      multiple
      placeholder="Select Styles"
      class="mr-2"
      style="width: 240px"
    >
      <el-option
        v-for="item in this.secondaryTags"
        :key="item.value"
        :label="item.label"
        :value="item.value"
      />
    </el-select>
    <el-select
      v-model="genre.value"
      :loading="this.tagsLoading"
      multiple
      filterable
      placeholder="Select Genre"
      class="mr-2"
      style="width: 240px"
    >
      <el-option
        v-for="item in this.commonTags"
        :key="item.value"
        :label="item.label"
        :value="item.value"
      />
    </el-select>
    <el-button type="primary" @click="submitFilter">Filter</el-button>
  </div>
</template>
<script>
  import {mapActions, mapGetters} from 'vuex'

  export default {
    data() {
      return {
        type: 'strict',
        union: true,
        styles: {
          value: ''
        },
        genre: {
          value: ''
        },
      }
    },
    computed: {
      ...mapGetters('adminMusicArtist', [
        'commonTags',
        'secondaryTags',
        'tagsLoading',
      ]),
    },
    methods: {
      ...mapActions('adminMusicArtist', [
        'loadTagsSelect',
        'switchTagsLoading',
      ]),
      ...mapActions('music', [
        'getArtists',
      ]),
      checkRules() {
        this.union = this.type === 'strict';
      },
      submitFilter() {
        this.getArtists({
          filters: {
            tags: this.genre.value,
            type: this.type,
            union: this.union
          }
        })
      }
    },
    mounted() {
      this.loadTagsSelect()
    }
  }
</script>
<style lang="scss">
  .music-filter {
    &__params {
      display: flex;
    }
  }
</style>
