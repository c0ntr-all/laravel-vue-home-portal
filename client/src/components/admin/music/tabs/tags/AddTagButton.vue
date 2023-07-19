<template>
  <q-btn
    class="q-mb-md"
    @click="dialog = true"
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
            dense
            filled
          />
          <q-input
            v-model="model.content"
            placeholder="Описание"
            type="textarea"
            dense
            filled
          />
          <q-checkbox
            v-model="model.common"
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
import { ref, defineEmits } from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";

const $q = useQuasar()
const emit = defineEmits(['tagCreated']);

const dialog = ref(false)
const model = ref({common: true})

const storeTag = async () => {
  emit('tagCreate', {
    name: model.value.name,
    content: model.value.content,
    common: model.value.common
  })
  // await api.put("music/admin/tags/store", {
  //   name: model.value.name,
  //   content: model.value.content,
  //   common: model.value.common
  // }).then(response => {
  //   const {data: {data}} = response
  //   // emit('tagCreated', data)
  //   $q.notify({
  //     type: 'positive',
  //     message: `Тег ${response.data.data.label} успешно добавлен!`
  //   })
  // }).catch(error => {
  //   if (error.response.status === 422) {
  //     const errors = error.response.data.errors
  //     Object.keys(errors).map(key => {
  //       $q.notify({
  //         type: 'negative',
  //         message: errors[key]
  //       })
  //     })
  //   } else {
  //     $q.notify({
  //       type: 'negative',
  //       message: error
  //     })
  //   }
  // }).finally(() => {
  //   model.value = {common: true}
  // })
}
</script>

<style scoped>

</style>
