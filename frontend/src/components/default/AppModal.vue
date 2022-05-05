<template>
  <transition name="modal">
    <div class="modal-mask" v-if="openModal === true">
      <div class="modal-wrapper">
        <div class="modal-container">
          <el-form>
            <div class="modal-header">
              <div class="modal-header__title">
                <h3><slot name="title"></slot></h3>
              </div>
              <div class="modal-header__close">
                <a class="modal-header__close-btn" href="#" @click.prevent="this.$emit('closeModal')">
                  <el-icon><close-bold /></el-icon>
                </a>
              </div>
            </div>
            <div class="modal-body">
              <slot name="content"></slot>
            </div>
            <div class="modal-footer">
              <slot name="footer"></slot>
            </div>
          </el-form>
        </div>
      </div>
    </div>
  </transition>
</template>
<script setup>
  import {
    CloseBold,
  } from '@element-plus/icons-vue'

</script>
<script>
  export default {
    props: {
      openModal: Boolean
    }
  }
</script>
<style lang="scss" scoped>
  .modal-mask {
    position: fixed;
    z-index: 1000;
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

  .modal-body {
    display: flex;
    flex-direction: column;
    row-gap: 1rem;
    margin-bottom: 1rem;
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
