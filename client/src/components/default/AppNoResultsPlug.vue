<template>
  <div class="bg-white q-pa-lg">
    <div class="bg-white q-pa-lg">
      <div class="result-empty">
        <div class="result-empty__content">
          <div class="result-empty__icon"><q-icon size="xl" name="search" /></div>
          <div class="result-empty__title">{{ props.title }}</div>
          <div class="result-empty__text">{{ props.body }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
 const props = defineProps({
   title: {
     type: String,
     default: 'No results for the query!'
   },
   body: {
     type: String,
     default: 'Just try another!'
   }
 })
</script>

<style>

</style>
