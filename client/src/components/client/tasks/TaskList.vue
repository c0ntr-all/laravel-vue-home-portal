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
      <div v-if="showAddForm === false" @click="openAddForm" class="list__add-button">
        <q-icon name="add" size="sm" />
        <span>Добавить карточку</span>
      </div>
      <div v-if="showAddForm === true" class="list__add-form">
        <q-input
          @keyup.enter="addNewTask"
          v-model="model.newCardName"
          ref="taskAddTextarea"
          type="textarea"
          input-style="height: 60px; resize: none"
          class="list__add-textarea q-mb-sm"
          dense
          outlined
        />
        <q-btn @click="addNewTask" label="Добавить карточку" color="secondary" class="q-mr-sm" no-caps dense />
        <q-btn @click="showAddForm = false" icon="close" color="danger" size="md" flat round dense />
      </div>
    </q-card-section>
  </q-card>
</template>
<script>
import {ref, nextTick} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

import TaskItem from 'src/components/client/tasks/TaskItem.vue'

export default {
  components: { TaskItem },
  props: ['list', 'items'],
  setup(props) {
    const $q = useQuasar()

    const showAddForm = ref(false)
    const taskAddTextarea = ref(null)
    const model = ref({
      listName: '',
      newCardName: ''
    })

    const openAddForm = () => {
      showAddForm.value = true
      nextTick(() => {
        taskAddTextarea.value.focus()
      })
    }
    //todo Change title to name in tasks entity
    const addNewTask = async () => {
      const cardName = model.value.newCardName
      model.value.newCardName = ''

      await API.put('tasks/store/' + props.list.id, {
        'title': cardName
      }).then(response => {
        $q.notify({
          type: 'positive',
          message: 'Карточка успешно добавлена!'
        })
        props.items.push(response.data.items)
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }
    return {
      showAddForm,
      taskAddTextarea,
      model,
      openAddForm,
      addNewTask
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
  &__add-button {
    display: flex;
    align-items: center;
    border-radius: 3px;
    padding: 5px 0;

    &:hover {
      cursor: pointer;
      background-color: #091e4214;
    }
  }
}
</style>
