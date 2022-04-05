<template>
  <div class="list">
    <div class="list__header">
      <div class="list__header--cover js-header-cover"
           @click="headerEdit"
           :class="{'is-hidden':isActive}">
      </div>
      <textarea class="list__header-name"
                spellcheck="false"
                dir="auto"
                maxlength="512"
                data-autosize="true"
                style="overflow: hidden; overflow-wrap: break-word; height: 28px;"
                :class="{'is-active':isActive}"
      >{{ this.list.title }}</textarea>
      <el-popover v-model:visible="visible" placement="left" :width="160">
        <p>Are you sure to delete this?</p>
        <div style="text-align: right; margin: 0">
          <el-button size="small" type="text" @click="visible = false">cancel</el-button>
          <el-button size="small" type="primary" @click="visible = false">confirm</el-button>
        </div>
        <template #reference>
          <el-button @click="visible = true" class="button" type="text">
            <el-icon :size="20"><edit /></el-icon>
          </el-button>
        </template>
      </el-popover>
    </div>
    <div class="list__body">
      <app-task v-for="item in items" :key="item.id" :item="item"></app-task>
    </div>
    <div class="list__footer">
      <el-form @submit.prevent="createTask(this.list.id)">
        <el-input placeholder="Введите заголовок!" v-model="newTaskTitle" />
      </el-form>
    </div>
  </div>
</template>

<script setup>
  import {
    Edit,
  } from '@element-plus/icons-vue'
  import { ref } from 'vue'
</script>

<script>
  import API from '../../utils/api'

  import AppTask from './AppTask'
  import {ref} from "vue";

  export default {
    data() {
      return {
        newTaskTitle: '',
        visible: ref(false),
        activeList: null
      }
    },
    emits: ['onTitleEdit'],
    props: {
      list: Object,
      items: Array,
      isActive: Boolean
    },
    methods: {
      createTask() {
        //TODO: Сделать человеческую обработку результата и ошибок из запроса
        let test = this.$store.dispatch('createTask', {
          title: this.newTaskTitle,
          list_id: this.list.id
        }).then(result => {
          this.$message.success("Карточка успешно добавлена!");
        }).catch(error => {
          this.$message.error(error);
        })
      },
      editHeader(event){
        let el = this.$refs.listHeader + this.listId;
        let textarea = (this.$refs.listHeader + this.listId).querySelector('textarea');
        textarea.focus();
        textarea.selectionStart = textarea.value.length;
        let target = e.target;
        if (el !== target && !el.contains(target) && textarea !== target){
          this.headerEdit = false
        }
      },
      headerEdit() {
        this.$emit('onTitleEdit')
      }
    },
    components: {AppTask},
    created(){
      //document.addEventListener('click', this.editHeader)
    },
    destroyed () {
      //document.removeEventListener('click', this.editHeader)
    },
  }
</script>

<style lang="scss" scoped>
  .list {
    background-color: #ebecf0;
    border-radius: 3px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    max-height: 100%;
    position: relative;
    white-space: normal;
    width: 272px;

    &__header {
      position: relative;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 8px;

      &-name {
        background: #0000;
        border-radius: 3px;
        box-shadow: none;
        font-weight: 600;
        height: 28px;
        margin: -4px 0;
        max-height: 256px;
        min-height: 20px;
        padding: 4px 8px;
        border: none;
        resize: none;
        overflow: hidden;
        overflow-wrap: break-word;

        &.is-active {
          background-color: #fff;
          box-shadow: inset 0 0 0 2px #0079bf;
        }
      }
      &--cover {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        top: 0;
        cursor: pointer;
      }
    }
    &__body {
      flex: 1 1 auto;
      margin: 0 4px;
      min-height: 0;
      overflow-x: hidden;
      overflow-y: auto;
      padding: 0 4px;
    }
    &__footer {
      padding: 10px 8px;
    }
  }
  .is-hidden {
    display: none;
  }
</style>
