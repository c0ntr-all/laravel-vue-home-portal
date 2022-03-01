<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container">
        <el-form>
          <div class="modal-header">
            <div class="modal-header__title">
              <el-input placeholder="Введите заголовок!" v-model="model.title" />
              <h2>{{ item.title }}</h2>
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
                  <textarea v-if="item.content" v-model="model.content">{{ item.content }}</textarea>
                  <p v-else>Добавьте более подробное описание...</p>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <el-button type="primary" @click="updateTask(item.id)" round>Сохранить</el-button>
          </div>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script setup>
  import {
    CloseBold
  } from '@element-plus/icons-vue'

</script>
<script>
  import API from '../../utils/api'

  export default {
    data() {
      return {
        model: {
          title: '',
          content: ''
        }
      }
    },
    props: {
      item: Object
    },
    methods: {
      async updateTask(id) {
        const {data} = await API.post(`api/auth/tasks/${id}/update`, {
          title: this.model.title,
          content: this.model.content
        })
        if(data) {
          this.$message.success("Изменения успешно сохранены!");
        }

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
