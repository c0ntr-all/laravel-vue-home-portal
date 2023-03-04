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
          <q-popup-edit
            ref="titlePopup"
            v-model="item.title"
            auto-save
            v-slot="scope"
          >
            <q-input
              v-model="scope.value"
              @keyup.enter="saveTitle(scope.value)"
              dense
              autofocus
              counter
            />
            <div class="q-pt-sm">
              <q-btn @click="saveTitle(scope.value)" label="Сохранить" color="primary" flat />
              <q-btn @click="titlePopup.cancel()" label="Отмена" color="primary" flat />
            </div>
          </q-popup-edit>
        </div>
        <q-btn @click="showModal = false" class="task__close" icon="close" size="md" flat rounded />
      </q-card-section>

      <q-separator dark />

      <q-card-section>
        <div v-if="item.content" v-html="item.content" class="task__content"></div>
        <div v-else class="task__content text-grey-5">Описание отсутствует!</div>
        <q-popup-edit
          ref="contentPopup"
          v-model="item.content"
          v-slot="scope"
        >
          <q-editor
            v-model="scope.value"
            min-height="5rem"
            autofocus
            @keyup.enter.stop
          />
          <div class="q-pt-sm">
            <q-btn @click="saveContent(scope.value)" label="Сохранить" color="primary" flat />
            <q-btn @click="contentPopup.cancel()" label="Отмена" color="primary" flat />
          </div>
        </q-popup-edit>
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
import {ref} from 'vue'
import {useQuasar} from "quasar"

import API from "src/utils/api"

export default {
  props: ['item'],
  setup(props) {
    const $q = useQuasar()

    const showModal = ref(false)
    const titlePopup = ref(null)
    const contentPopup = ref(null)
    const saveTitle = value => {
      API.patch(`tasks/${props.item.id}/update`, {
        title: value
      }).then(response => {
        $q.notify({
          type: 'positive',
          message: 'Имя карточки успешно обновлено!'
        })
        titlePopup.value.set()
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
        titlePopup.value.cancel()
      })
    }
    const saveContent = value => {
      API.patch(`tasks/${props.item.id}/update`, {
        content: value
      }).then(response => {
        $q.notify({
          type: 'positive',
          message: 'Описание успешно обновлено!'
        })
        contentPopup.value.set()
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
        contentPopup.value.cancel()
      })
    }

    return {
      showModal,
      titlePopup,
      contentPopup,
      saveTitle,
      saveContent
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
  &__title {
    width: 100%;

    &:hover {
      cursor: pointer;
      background: #f4f5f7;
    }
  }
  &__content {
    &:hover {
      cursor: pointer;
      background: #f4f5f7;
    }
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
