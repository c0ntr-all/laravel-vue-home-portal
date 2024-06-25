<template>
  <q-btn
    class="q-mb-md"
    @click="addTagHandler"
    icon="add"
    label="Добавить тег"
    size="md"
    color="primary"
    dense
  />
  <q-dialog v-model="dialog">
    <q-card class="tag-dialog" style="width: 100%">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Create tag</div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <div class="q-gutter-md">
          <q-input
            v-model="model.name"
            placeholder="Name"
            ref="tagNameRef"
            dense
            filled
          />
          <q-input
            v-model="model.description"
            placeholder="Описание"
            type="textarea"
            dense
            filled
          />
          <q-checkbox
            v-model="model.is_base"
            label="Основной тег"
            color="primary"
            left-label
            dense
          />
        </div>
      </q-card-section>

      <q-card-section>
        <q-btn @click="storeTag" color="primary">Create</q-btn>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
import {nextTick, ref} from "vue";
const emit = defineEmits(['tagCreate']);

const dialog = ref(false)
const model = ref({is_base: true})
const tagNameRef = ref(null)

const addTagHandler = () => {
  dialog.value = true

  nextTick(() => {
    tagNameRef.value.$el.querySelector('input').focus()
  })
}

const storeTag = async () => {
  emit('tagCreate', {
    name: model.value.name,
    description: model.value.description,
    is_base: model.value.is_base
  })
}
</script>

<style scoped>

</style>
