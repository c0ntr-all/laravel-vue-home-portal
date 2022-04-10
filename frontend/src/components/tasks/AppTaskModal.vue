<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container">
        <el-form>
          <div class="modal-header">
            <div class="modal-header__title">
              <div class="modal-header__title-text" v-show="isEditTitle === false">
                <h3>{{ item.title }}</h3>
                <el-button @click="openEditTitle(item.title)" class="button" type="text">
                  <el-icon><edit /></el-icon>
                </el-button>
              </div>
              <div class="modal-header__title-input" v-show="isEditTitle === true">
                <el-input v-model="item.title" />
                <el-button type="primary" @click="editTitle" >Сохранить</el-button>
                <el-button type="danger" :icon="CloseBold" @click="closeEditTitle" circle></el-button>
              </div>
            </div>
            <div class="modal-header__close">
              <a class="modal-header__close-btn" href="#" @click.prevent="this.$emit('closeModal')">
                <el-icon><close-bold /></el-icon>
              </a>
            </div>
          </div>
          <div class="modal-body">
            <div class="modal-block">
                <div class="modal-block__title"><h3>Описание</h3></div>
                <div class="modal-block__content">
                  <el-input v-model="item.content" type="textarea">{{ item.content }}</el-input>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <el-button type="primary" @click="updateTask(item)" round>Сохранить</el-button>
          </div>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script setup>
  import {
    CloseBold,
    Edit
  } from '@element-plus/icons-vue'

</script>
<script>

  export default {
    data() {
      return {
        isEditTitle: false,
        legacyTitle: null
      }
    },
    props: {
      item: Object
    },
    methods: {
      updateTask(item) {
        this.$store.dispatch('updateTask', item).then(result => {
          this.$message.success("Карточка успешно обновлена!");
        }).catch(error => {
          this.$message.error(error);
        })
      },
      editTitle() {
        console.log('editTitle')
      },
      openEditTitle(legacyTitle) {
        this.isEditTitle = true
        this.legacyTitle = legacyTitle
      },
      closeEditTitle() {
        this.isEditTitle = false
        this.item.title = this.legacyTitle
      }
    }
  }
</script>

<style lang="scss" scoped>
  .modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
  }

  .modal-wrapper {
    display: table-cell;
    vertical-align: middle;
  }

  .modal-container {
    width: 768px;
    margin: 0px auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    font-family: Helvetica, Arial, sans-serif;
  }

  .modal-header {
    display: flex;
    justify-content: space-between;
    margin-top: 0;
    color: #42b983;

    &__close-btn {
      display: flex;
      padding: 10px;
      text-decoration: none;
      border-radius: 50%;
      color: #000000;

      &:hover {
        background: #e7e5e5;
      }
    }

    &__title-text {
      display: flex;
      align-items: center;
      column-gap: 10px;
    }
  }

  .modal-block {
    margin-bottom: 1rem;
  }

  .modal-default-button {
    display: block;
    margin-top: 1rem;
  }

  .modal-enter-active,
  .modal-leave-active {
    transition: opacity 0.5s ease;
  }

  .modal-enter-from,
  .modal-leave-to {
    opacity: 0;
  }
</style>
