<template>
  <q-dialog
    v-model="show"
    @update="$emit('update:modelValue', $event.target.value)"
  >
    <q-card style="width: 768px; max-width: 80vw;">
      <q-card-section>
        <p class="text-h6 q-ma-none"><slot name="header" /></p>
      </q-card-section>

      <q-separator />

      <q-card-section>
        <slot name="body" />
      </q-card-section>

      <q-card-actions align="right" class="bg-white">
        <slot name="footer" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { ref, watchEffect, watch } from "vue"

const props = defineProps({
  modelValue: Boolean,
})
const emit = defineEmits(['update:modelValue']);

const show = ref(true)

watchEffect(() => {
  show.value = props.modelValue;
});
watch(show, (newVal) => {
  if (newVal !== props.modelValue) {
    emit('update:modelValue', newVal);
  }
});
</script>

<style lang="scss" scoped>

</style>
