<template>
  <div class="text task">
    <a href="#" class="task__link" @click.prevent="openTask = true">{{ item.title }}</a>
    <transition name="modal">
      <app-task-modal
        v-if="openTask"
        @closeModal="openTask = false"
        :item="item"
      ></app-task-modal>
    </transition>
  </div>
</template>
<script>
  import AppTaskModal from './AppTaskModal'

  export default {
    data() {
      return {
        openTask: false
      }
    },
    props: {
      item: Object
    },
    components: {AppTaskModal}
  }
</script>
<style lang="scss">
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .text {
    font-size: 14px;
  }
  .task {
    word-break: break-all;
    margin-bottom: 8px;

    &__link {
      display: block;
      padding: 8px;
      background-color: #fff;
      text-decoration: none;
      color: #000;
      border-radius: 3px;
      box-shadow: 0 1px 0 #091e4240;

      &:hover {
        background-color: #f4f5f7;
        border-bottom-color: #091e4240;
      }
    }
  }
</style>
