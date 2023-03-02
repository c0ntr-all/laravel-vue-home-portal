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
      <q-card-section>
        <div class="task__title text-h6">{{ item.title }}</div>
      </q-card-section>

      <q-separator dark />

      <q-card-section>
        <div class="task__content">{{ item.content }}</div>
      </q-card-section>

      <q-card-section v-if="item.comments.length">
        <div class="task__comments">
          <div class="text-h6 q-mb-sm">Комментарии</div>
          <div class="comments-list column q-gutter-sm">
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
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script>
import {ref} from 'vue'
export default {
  props: ['item'],
  setup() {
    const showModal = ref(false)
    return {
      showModal
    }
  }
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
.task-item {
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
