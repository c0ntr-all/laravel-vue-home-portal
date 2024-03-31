<template>
  <AppModal
    v-model="show"
    @update="$emit('update:modelValue', $event.target.value)"
  >
    <template #header>{{ text }} напоминание</template>
    <template #body>
      <q-form class="q-gutter-y-md column">
        <q-input
          v-model="model.title"
          label="Заголовок"
          :rules="[ val => val && val.length > 0 || 'Поле title должно быть заполнено!']"
          outlined
          dense
        />
        <q-editor v-model="model.content" min-height="5rem" />
        <q-input v-model="model.datetime" outlined filled>
          <template v-slot:prepend>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                <q-date v-model="model.datetime" mask="YYYY-MM-DD HH:mm">
                  <div class="row items-center justify-end">
                    <q-btn label="Close" color="primary" v-close-popup flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
          <template v-slot:append>
            <q-icon name="access_time" class="cursor-pointer">
              <q-popup-proxy transition-show="scale" transition-hide="scale" cover>
                <q-time v-model="model.datetime" mask="YYYY-MM-DD HH:mm" format24h>
                  <div class="row items-center justify-end">
                    <q-btn label="Close" color="primary" v-close-popup flat />
                  </div>
                </q-time>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
        <div class="relative-position q-gutter-sm" style="height: 48px">
          <q-radio
            v-for="group in groups"
            :key="group.color"
            v-model="model.group"
            :val="group.color"
            :label="group.name"
            :color="group.color"
          />
          <q-inner-loading :showing="getGroupsLoading" />
        </div>
        <div class="justify-start">
          <q-toggle
            v-model="model.is_active"
            checked-icon="add"
            unchecked-icon="remove"
            label="Active"
            left-label
          />
        </div>
      </q-form>
    </template>
    <template #footer>
      <q-btn label="Сохранить" color="primary" @click="updateRemind" :loading="loading" />
      <!--Todo: Need to write cancel update handler for returning previous values to model-->
      <q-btn label="Cancel" v-close-popup />
    </template>
  </AppModal>
</template>

<script setup>
import { ref, onMounted, watchEffect, watch } from "vue"
import { useQuasar } from "quasar"
import { api } from "boot/axios"
import AppModal from "src/components/extra/AppModal.vue"

const $q = useQuasar()
const props = defineProps({
  modelValue: Boolean,
  remindToUpdate: Object
});
const emit = defineEmits(['update:modelValue', 'updated']);

const mode = props.remindToUpdate ? 'update' : 'create'
const text = mode === 'create' ? 'Создать' : 'Редактировать'
const loading = ref(false)
const groups = ref([])
const groupsLoading = ref(false)
const model = ref(props.remindToUpdate ? props.remindToUpdate : {
  title: '',
  content: '',
  group: '',
  datetime: '',
  is_active: true
})

const show = ref(props.modelValue)
const getGroups = async () => {
  if (!groups.value.length) {
    groupsLoading.value = true

    await api.get('reminds/groups').then(response => {
      groups.value = response.data.items
    }).catch(error => {
      $q.notify({
        type: 'negative',
        message: 'There is a problem with loading groups!'
      })
    }).finally(() => {
      groupsLoading.value = false
    })
  }
}

const updateRemind = async () => {
  loading.value = true

  await api.patch(`reminds/${model.value.id}`, {
    ...model.value
  }).then(response => {
    emit('updated', response.data)
    $q.notify({
      type: 'positive',
      message: `Remind has been updated!`
    })
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  }).finally(() => {
    loading.value = false
  })
}

watchEffect(() => {
  show.value = props.modelValue;
});

watch(show, (newVal) => {
  if (newVal !== props.modelValue) {
    emit('update:modelValue', newVal);
  }
  if (newVal === false) {
    model.value = {}
  }
});

onMounted(() => {
  getGroups()
})
</script>

<style scoped>

</style>
