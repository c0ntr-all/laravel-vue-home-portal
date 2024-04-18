<template>
  <q-card class="q-mb-md" flat bordered>
    <q-card-section class="column q-gutter-sm">
      <template v-if="loading">
        <q-skeleton v-for="q in Math.floor(Math.random() * 10)" width="500px" height="70px" square/>
      </template>
      <template v-else>
        <div v-for="(group, index) in remindsStore.groupsForSettings" class="flex" style="max-width: 550px; width: 100%">
          <q-card class="flex justify-between items-center q-mr-sm" style="flex-basis: 434px">
            <q-card-section>
              <q-input
                v-model="group.name"
                :rules="[ val => val.length >= 3 || 'Please use minimum 3 characters' ]"
                class="q-pa-none"
                borderless
                dense
              />
            </q-card-section>
            <q-card-section class="flex items-center">
              <div class="color-square q-mr-sm " :style="`background-color:${group.color}`"></div>
              <q-input
                v-model="group.color"
                :rules="['anyColor']"
                class="q-pa-none"
                style="width: 100px"
                dense
              >
                <template v-slot:append>
                  <q-icon name="colorize" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-color v-model="group.color" format-model="hex"/>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </q-card-section>
          </q-card>
          <q-btn @click="!group.id ? removeRow(index) : deleteRemindGroup(group.id)" class="flex items-center q-px-sm" flat>
            <q-icon name="close" size="sm" color="red"/>
          </q-btn>
          <q-btn v-if="!group.id || group.changed" @click="saveRemindGroup(group)" class="flex items-center q-px-sm" flat>
            <q-icon name="save" size="sm" color="primary"/>
          </q-btn>
        </div>

        <q-separator/>

        <q-card-actions>
          <q-btn @click="addRow" label="Добавить группу" color="primary"/>
        </q-card-actions>
      </template>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue"
import { api } from "src/boot/axios"
import { useQuasar } from "quasar"
import { useRemindsStore } from "stores/modules/reminds"

const $q = useQuasar()
const remindsStore = useRemindsStore()

let loading = ref(false)

const loadGroups = async () => {
  loading.value = true
  remindsStore.getGroups().catch(() => {
    $q.notify({
      type: 'negative',
      message: error.response.data.message
    })
  }).finally(() => {
    loading.value = false
  })
}

const deleteRemindGroup = async id => {
  await api.delete(`reminds/groups/${id}`).then(response => {
    let idx = remindsStore.groups.findIndex(group => group.id === id);

    if (idx !== -1) {
      remindsStore.groups.splice(idx, 1);
    }
    $q.notify({
      type: 'positive',
      message: response.data.message
    })
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: error.response.data.message
    })
  })
}

const saveRemindGroup = async group => {
  await api.put('reminds/groups', group).then(response => {
    group.id = response.data.data.id
    group.changed = false

    $q.notify({
      type: 'positive',
      message: response.data.message
    })
  }).catch(error => {
    $q.notify({
      type: 'negative',
      message: error.response.data.message
    })
  })
}

const addRow = () => {
  const lastItem = remindsStore.groups[remindsStore.groups.length - 1]

  if (lastItem.name && lastItem.color || remindsStore.groups.length === 0) {
    remindsStore.groups.push({name: '', color: ''})
  }
}

const removeRow = index => {
  remindsStore.groups.splice(index, 1)
}

onMounted(() => {
  if (!remindsStore.groups.length) {
    loadGroups().then(() => {
      const clonedItems = computed(() => JSON.parse(JSON.stringify(remindsStore.groups)));
      watch(clonedItems, (newValue, oldValue) => {
        newValue.forEach((newGroup, index) => {
          const oldGroup = oldValue.find(group => group.id === newGroup.id);
          if (oldGroup) {
            if (newGroup.name !== oldGroup.name || newGroup.color !== oldGroup.color) {
              remindsStore.groups[index].changed = true
            }
          }
        })
      }, { deep: true });
    })
  }
})
</script>

<style lang="scss" scoped>
.color-square {
  width: 20px;
  height: 20px;
}
</style>
