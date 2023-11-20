<template>
  <q-card class="q-mb-md" flat>
    <q-card-section>
      <div class="row">
        <div class="col-lg-4">
          <q-form @submit="$emit('search', searchText)">
            <q-input
              v-model="searchText"
              label="Search"
              outlined
              dense
            >
              <template v-slot:append>
                <q-icon v-if="searchText !== ''" name="close" @click="reset" class="cursor-pointer" />
              </template>
              <template v-slot:after>
                <q-icon name="search" @click="$emit('search', searchText)" class="cursor-pointer" />
              </template>
            </q-input>
          </q-form>
        </div>
        <div class="col-lg-8">
          <div class="row justify-end">
            <q-btn-toggle
              v-model="cardMode"
              @click="$emit('switchCardMode', cardMode)"
              class="border-grey"
              toggle-color="primary"
              color="white"
              text-color="black"
              :options="[
                  {value: 'card', slot: 'card'},
                  {value: 'row', slot: 'row'}
                ]"
              no-caps
              unelevated
              rounded
              flat
              dense
            >
              <template v-slot:card>
                <div class="row items-center no-wrap">
                  <q-icon name="toc" size="md" right />
                </div>
              </template>

              <template v-slot:row>
                <div class="row items-center no-wrap">
                  <q-icon name="view_cozy" size="md" right />
                </div>
              </template>
            </q-btn-toggle>
          </div>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { ref } from "vue"

const emit = defineEmits(['reset', 'search', 'switchCardMode']);

const searchText = ref('')
const cardMode = ref('row')

const reset = () => {
  searchText.value = ''
  emit('reset')
}
</script>

<style scoped>

</style>
