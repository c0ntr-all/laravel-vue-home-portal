<template>
  <AppModal
    v-model="show"
    @update="$emit('update:modelValue', $event.target.value)"
    :actionsAlign="mode === 'update' ? 'between' : 'right'"
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
          <q-select
            v-model="model.group"
            :options="remindsStore.groupsForSelect"
            label="Группа"
            :options-html="true"
            filled
          />
          <q-inner-loading :showing="groupsLoading" />
        </div>

        <div class="justify-start">
          <q-toggle
            v-model="model.is_regular"
            checked-icon="alarm"
            unchecked-icon="remove"
            label="Регулярное?"
            left-label
          />
        </div>

        <div v-if="model.is_regular" class="relative-position q-gutter-sm">
          <q-select
            v-model="model.interval"
            :options="remindsStore.intervals"
            label="Интервал"
            :options-html="true"
            filled
          />
          <q-inner-loading :showing="intervalsLoading" />
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
    <template #footer="">
      <div v-if="mode === 'update'">
        <q-btn label="Удалить" color="red" @click="confirmDeleteRemind = true" :loading="loadingDel" flat />
        <q-dialog v-model="confirmDeleteRemind" persistent>
          <q-card>
            <q-card-section class="row items-center">
              <q-avatar icon="delete" color="primary" text-color="white" />
              <span class="q-ml-sm">Вы точно хотите удалить напоминание?</span>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn flat label="Cancel" v-close-popup />
              <q-btn flat label="Удалить" @click="deleteRemind" color="red" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>
      </div>
      <div>
        <q-btn class="q-mr-sm" :label="text" color="primary" @click="saveRemind" :loading="loading" />
        <q-btn label="Cancel" v-close-popup />
      </div>
    </template>
  </AppModal>
</template>

<script setup>
import { ref, onMounted, watchEffect, watch, computed } from "vue"
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

const editFields = ['title', 'content', 'group', 'datetime', 'is_active', 'is_regular', 'interval']
const mode = ref(props.remindToUpdate ? 'update' : 'create');
const text = computed(() => {
  return mode.value === 'create' ? 'Создать' : 'Сохранить';
});
const loading = ref(false)
const loadingDel = ref(false)
const groupsLoading = ref(false)
const intervalsLoading = ref(false)
const model = ref({})
const rawRemind = ref({}) // Сырой объект с только обновляемыми полями, чтобы потом корректно получить измененные свойства
const show = ref(props.modelValue)
const confirmDeleteRemind = ref(false)

const prepareModel = () => {
  if (mode.value === 'update') {
    rawRemind.value = JSON.parse(JSON.stringify(props.remindToUpdate))
    const preparedGroup = rawRemind.value.group ? {
        label: `<div class="flex items-center q-gutter-sm"><div style="background-color:${rawRemind.value.group.color}; width: 50px; height: 20px"></div><div>${rawRemind.value.group.name}</div></div>`,
        value: rawRemind.value.group.id
      } : {
      label: `<div class="flex items-center q-gutter-sm"><div style="background-color:#fff; width: 50px; height: 20px"></div><div>Без группы</div></div>`,
      value: null
    }
    rawRemind.value.group = preparedGroup
    editFields.forEach(key => {
      if (rawRemind.value.hasOwnProperty(key)) {
        model.value[key] = rawRemind.value[key]
      } else if (key === 'group') {
        model.value[key] = preparedGroup
      }
    })
  } else {
    model.value = editFields.reduce((acc, key) => {
      switch (key) {
        case 'is_active':
          acc[key] = true
          break
        case 'is_regular':
          acc[key] = false
          break
        default: ''
      }
      return acc
    }, {})
  }
}

const loadGroups = async () => {
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

const loadIntervals = async () => {
  intervalsLoading.value = true
  await remindsStore.getIntervals().catch(error => {
    $q.notify({
      type: 'negative',
      message: 'There is a problem with loading intervals!'
    })
  }).finally(() => {
    intervalsLoading.value = false
  })
}

const saveRemind = () => {
  const data = getChanges(rawRemind.value, model.value)

  // Convert group to group_id
  if (data.group) {
    data.group_id = data.group.value
    delete data.group
  }

  if (data.interval) {
    data.interval = data.interval.value
  }

  switch(mode.value) {
    case 'create':
      createRemind(data)
      break;
    case 'update':
      updateRemind(data)
      break;
  }
}

const createRemind = async data => {
  loading.value = true

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
    show.value = false
  })
}

const updateRemind = async data => {
  loading.value = true

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
    show.value = false
  })
}

const deleteRemind = async () => {
  loadingDel.value = true

  await api.delete(`reminds/${props.remindToUpdate.id}`).then(response => {
    emit('deleted', props.remindToUpdate.id)
    $q.notify({
      type: 'positive',
      message: response.data.message
    })
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: `Server Error: ${error.response.data.message}`
    })
  }).finally(() => {
    loadingDel.value = false
    show.value = false
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
  console.log(remindsStore.intervals)
  console.log(remindsStore.groups)
  prepareModel()
  if (!remindsStore.groups.length) {
    loadGroups()
  }
  if (!remindsStore.intervals.length) {
    loadIntervals()
  }
})
</script>

<style scoped>

</style>
