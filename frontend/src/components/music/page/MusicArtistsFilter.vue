<template>
  <div class="music-filter mb-3">
    <h3>Filter</h3>
    <blockquote class="tip danger">
      <p class="tip__title">Внимание!</p>
      <p>Дополнительный стиль временно не работает!</p>
    </blockquote>
    <div class="music-filter__params">
      <el-radio-group class="mb-2 mr-2" v-model="type" size="default">
        <el-radio-button label="strict">Точное совпадение</el-radio-button>
        <el-radio-button label="hierarchical">Иерархический поиск</el-radio-button>
      </el-radio-group>
      <el-checkbox v-model="union" label="Совместный" border />
    </div>
    <el-select
      v-model="styles.value"
      multiple
      placeholder="Select Styles"
      class="mr-2"
      style="width: 240px"
    >
      <el-option
        v-for="item in styles.options"
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
        v-for="item in genre.options"
        :key="item.value"
        :label="item.label"
        :value="item.value"
      />
    </el-select>
    <el-button type="primary" @click="submitFilter">Filter</el-button>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        type: 'strict',
        union: true,
        styles: {
          options: [
            {
              label: 'Progressive',
              value: 'Progressive'
            },
            {
              label: 'Technical',
              value: 'Technical'
            },
            {
              label: 'Modern',
              value: 'Modern'
            },
            {
              label: 'Folk',
              value: 'Folk'
            },
            {
              label: 'Melodic',
              value: 'Melodic'
            },
            {
              label: 'Industrial',
              value: 'Industrial'
            },
            {
              label: 'Blackened',
              value: 'Blackened'
            },
          ],
          value: ''
        },
        genre: {
          options: [
            {
              label: 'Death Metal',
              value: 'Death Metal'
            },
            {
              label: 'Groove Metal',
              value: 'Groove Metal'
            },
            {
              label: 'Black Metal',
              value: 'Black Metal'
            },
            {
              label: 'Nu Metal',
              value: 'Nu Metal'
            },
            {
              label: 'Metalcore',
              value: 'Metalcore'
            },
            {
              label: 'Deathcore',
              value: 'Deathcore'
            },
            {
              label: 'Metal',
              value: 'Metal'
            },
            {
              label: 'Hardcore',
              value: 'Hardcore'
            },
            {
              label: 'Math Metal',
              value: 'Math Metal'
            },
            {
              label: 'Power Metal',
              value: 'Power Metal'
            },
            {
              label: 'Heavy Metal',
              value: 'Heavy Metal'
            },
          ],
          value: ''
        },
      }
    },
    methods: {
      submitFilter() {
        let filters = {
          tags: this.genre.value,
          type: this.type,
          union: this.union
        }
        this.$store.dispatch('music/getArtists', {
          filters: filters
        })
      }
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
