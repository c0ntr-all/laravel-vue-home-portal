<template>
  <q-card class="list">
    <q-card-section class="list__header">
      <p>{{ list.title }}</p>
    </q-card-section>
    <q-separator dark />
    <q-card-section class="list__body">
      <TaskItem v-for="item in items" :key="item.id" :item="item" />
    </q-card-section>
    <q-card-section class="list__footer">
    </q-card-section>
  </q-card>
</template>
<script>
import TaskItem from 'src/components/client/tasks/TaskItem.vue'

export default {
  components: { TaskItem },
  props: ['list', 'items'],
  setup() {
    return {

    }
  }
}
</script>
<style lang="scss" scoped>
.list {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 272px;
  max-height: 100%;
  border-radius: 3px;
  white-space: normal;
  background-color: #ebecf0;
  box-sizing: border-box;

  &__header {
    padding: 8px;

    p {
      margin: 0;
    }

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
