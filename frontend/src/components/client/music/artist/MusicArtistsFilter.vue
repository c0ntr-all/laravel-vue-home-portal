<template>
  <div class="music-filter mb-3">
    <h3>Filter</h3>
    <div class="music-filter__params">
      <el-radio-group class="mb-2 mr-2" v-model="type" size="default" @change="checkRules()">
        <el-radio-button label="strict">Точное совпадение</el-radio-button>
        <el-radio-button label="hierarchical">Иерархический поиск</el-radio-button>
      </el-radio-group>
      <el-checkbox v-model="union" :disabled="type !== 'strict'" border>Совместный</el-checkbox>
    </div>

    <el-select v-model="model.secondary" placeholder="Select Styles" class="mr-2" style="width: 240px">
      <el-option v-for="item in tags.secondary" :key="item.value" :label="item.label" :value="item.value"/>
    </el-select>

    <el-select v-model="model.common" placeholder="Select Genre" class="mr-2" style="width: 240px" multiple filterable>
      <el-option v-for="item in tags.common" :key="item.value" :label="item.label" :value="item.value"/>
    </el-select>

    <el-button type="primary" @click="submitFilter">Filter</el-button>
  </div>
</template>
<script>
  import {mapActions} from 'vuex'

  export default {
    data() {
      return {
        type: 'strict',
        union: true,
        tags: {
          common: {},
          secondary: {}
        },
        model: {
          common: '',
          secondary: ''
        }
      }
    },
    methods: {
      ...mapActions('tags', [
        'getTagsSelect',
      ]),
      ...mapActions('music', [
        'getArtists',
      ]),
      loadTagsSelect() {
        this.getTagsSelect().then(response => {
          this.tags.common = response.tags.common
          this.tags.secondary = response.tags.secondary
        }).catch(error => {
          this.$message.error(error)
        })
      },
      checkRules() {
        this.union = this.type === 'strict';
      },
      submitFilter() {
        this.getArtists({
          filters: {
            tags: this.model.secondary ? this.model.common.concat(this.model.secondary) : this.model.common,
            type: this.type,
            union: this.union
          }
        })
      },
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
