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
        <div class="relative-position q-gutter-sm">
            <q-radio
              v-for="group in remindsStore.groups"
              :key="group.color"
              v-model="model.group_id"
              :val="group.id"
              :label="group.name"
              :color="group.color"
            />
          <q-inner-loading :showing="groupsLoading" />
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
      <q-btn label="Сохранить" color="primary" @click="saveRemind" :loading="loading" />
      <!--Todo: Need to write cancel update handler for returning previous values to model-->
      <q-btn label="Cancel" v-close-popup />
    </template>
  </AppModal>
</template>

<script setup>
import { ref, onMounted, watchEffect, watch } from "vue"
import { useQuasar } from "quasar"
import { api } from "boot/axios"
import { useRemindsStore } from "stores/modules/reminds"
import getChanges from "src/utils/getChanges"
import AppModal from "src/components/extra/AppModal.vue"

const $q = useQuasar()
const props = defineProps({
  modelValue: Boolean,
  remindToUpdate: Object
});
const emit = defineEmits(['update:modelValue', 'updated', 'created']);
const remindsStore = useRemindsStore()

const editFields = ['title', 'content', 'group_id', 'datetime', 'is_active']
const mode = props.remindToUpdate ? 'update' : 'create'
const text = mode === 'create' ? 'Создать' : 'Редактировать'
const loading = ref(false)
const groupsLoading = ref(false)
const model = ref({})
const rawRemind = ref({}) // Сырой объект с только обновляемыми полями, чтобы потом корректно получить измененные свойства
const show = ref(props.modelValue)

const prepareModel = () => {
  if (mode === 'update') {
    rawRemind.value = JSON.parse(JSON.stringify(props.remindToUpdate))
    editFields.forEach(key => {
      if (rawRemind.value.hasOwnProperty(key)) {
        model.value[key] = rawRemind.value[key]
      } else if (key === 'group_id') {
        model.value[key] = rawRemind.value['group'] ? rawRemind.value['group']['id'] : null
      }
    })
  } else {
    model.value = editFields.reduce((acc, key) => {
      if (key === 'is_active') {
        acc[key] = true
      } else {
        acc[key] = ''
      }
      return acc
    }, {})
  }
}

const getGroups = async () => {
  groupsLoading.value = true
  await remindsStore.getGroups().catch(error => {
    $q.notify({
      type: 'negative',
      message: 'There is a problem with loading groups!'
    })
  }).finally(() => {
    groupsLoading.value = false
  })
}

const saveRemind = () => {
  switch(mode) {
    case 'create':
      createRemind()
      break;
    case 'update':
      updateRemind()
      break;
  }
}

const createRemind = async () => {
  loading.value = true
  const data = model.value

  await api.post(`reminds`, data).then(response => {
    emit('created', response.data)
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

const updateRemind = async () => {
  loading.value = true
  const data = getChanges(rawRemind.value, model.value)

  await api.patch(`reminds/${props.remindToUpdate.id}`, data).then(response => {
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
  prepareModel()
  if (!remindsStore.groups.length) {
    getGroups()
  }
})
</script>

<style scoped>

</style>
