<template>
  <template v-if="col.name === 'title'">
    <div class="flex items-center">
      <span>{{ col.value }}</span>
      <span v-if="row.is_regular" class="q-pl-xs">
        <q-icon name="alarm" color="green" size="xs" />
      </span>
    </div>
  </template>
  <template v-else-if="col.name === 'active'">
    <q-icon v-if="row.is_active" name="done" color="green" size="sm" />
    <q-icon v-else name="close" color="red" size="sm" />
  </template>
  <template v-else-if="col.name === 'time_left'">
    {{ col.value.message }}
  </template>
  <template v-else>
    {{ col.value }}
  </template>
</template>
<script>
export default {
  props: ['col', 'row']
}
</script>
<style>

</style>
