<template>
  <div class="text task-item">
    <a
      href="#"
      class="task-item__link"
      @click.prevent="showModal = true"
    >{{ item.title }}</a>
  </div>

  <q-dialog v-model="showModal">
    <q-card style="width: 768px; max-width: 80vw;">
      <q-card-section class="task__header">
        <div class="task__title text-h6">
          {{ item.title }}
          <q-popup-edit v-model="item.title" auto-save v-slot="scope">
            <q-input v-model="scope.value" dense autofocus counter @keyup.enter="scope.set" />
          </q-popup-edit>
        </div>
        <q-btn @click="showModal = false" class="task__close" icon="close" size="md" flat rounded />
      </q-card-section>

      <q-separator dark />

      <q-card-section>
        <div v-if="item.content" class="task__content">{{ item.content }}</div>
        <p v-else class="text-grey-5">Описание отсутствует!</p>
      </q-card-section>

      <q-card-section>
        <div class="task__comments">
          <div class="text-h6 q-mb-sm">Комментарии</div>
          <div v-if="item.comments.length" class="comments-list column q-gutter-sm">
            <q-card v-for="comment in item.comments" :key="comment.id">
              <q-card-section class="flex justify-between">
                <span>{{ comment.user_name }}</span>
                <time class="time">{{ comment.created_at }}</time>
              </q-card-section>
              <q-card-section>
                {{ comment.content }}
              </q-card-section>
            </q-card>
          </div>
          <p v-else class="text-grey-5">Комментарии отсутствуют!</p>
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script>
import {ref, watch} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

export default {
  props: ['item'],
  setup(props) {
    const $q = useQuasar()

    const showModal = ref(false)
    watch(props.item, (newValue, oldValue) => {
      API.post(`tasks/${props.item.id}/update`, {
        title: props.item.title
      }).then(response => {
        $q.notify({
          type: 'positive',
          message: 'Имя карточки успешно обновлено!'
        })
      }).catch(error => {
        // todo now the old value is the same as the new. Need to catch old value and assign it to props if error fired
        props.item.title = oldValue.title
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    })
    return {
      showModal
    }
  }
}
</script>
<style lang="scss">
.task {
  &__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  &-item {
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
}
.text {
  font-size: 14px;
}
</style>
