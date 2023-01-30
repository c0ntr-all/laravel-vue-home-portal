<template>
  <div class="text-h6">Теги</div>
  <div class="q-pa-md q-gutter-sm">
    <q-input ref="filterRef" v-model="filter" label="Filter" outlined dense>
      <template v-slot:append>
        <q-icon v-if="filter !== ''" name="clear" class="cursor-pointer" @click="resetFilter" />
      </template>
    </q-input>

    <q-tree
      :nodes="tags"
      node-key="label"
      :filter="filter"
      default-expand-all
    >
      <template v-slot:default-header="prop">
        <div class="tag row justify-center items-center">
          <div class="tag__name q-ma-none">
            {{ prop.node.label }}
          </div>
          <div class="tag__actions q-gutter-x-xs">
            <q-btn size="xs" color="primary" dense>
              <q-icon name="add" size="15px"></q-icon>
            </q-btn>
            <q-btn size="xs" color="secondary" dense>
              <q-icon name="edit" size="15px"></q-icon>
            </q-btn>
            <q-btn size="xs" color="red" dense>
              <q-icon name="delete" size="15px"></q-icon>
            </q-btn>
          </div>
        </div>
      </template>
    </q-tree>
  </div>
</template>
<script>
 import { ref, onMounted } from 'vue'
 import API from "src/utils/api";

 export default {
   setup() {
     const tags = ref([])
     const selectedTag = ref('')
     const getTags = async () => {
       await API.post('music/tags/tree')
         .then(response => {
            tags.value = response.data.tags.common
         }).catch(error => {
           return false
         })
     }
     onMounted(() => {
       getTags()
     })
     return {
       tags,
       selectedTag,
       getTags
     }
   }
 }
</script>
<style lang="scss" scoped>
  .tag {
    &__actions {
      margin-left: 5px;
    }
  }
</style>
