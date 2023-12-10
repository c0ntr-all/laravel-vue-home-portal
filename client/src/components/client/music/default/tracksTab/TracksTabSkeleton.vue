<template>
  <q-markup-table>
    <thead>
    <tr>
      <th class="text-left">
        <q-skeleton type="text" width="15px" />
      </th>
      <th class="text-right">
      </th>
      <th class="text-right">
        <q-skeleton type="text" width="65px" />
      </th>
      <th class="text-right">
        <q-skeleton type="text" width="65px" />
      </th>
      <th class="text-right">
        <q-skeleton type="text" width="65px" />
      </th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="n in 30" :key="n">
      <td class="text-left">
        <q-skeleton type="text" width="15px" />
      </td>
      <td class="text-right">
        <q-skeleton type="text" width="100px" />
      </td>
      <td class="text-right">
        <q-skeleton type="text" width="200px" />
      </td>
      <td class="text-left">
        <q-skeleton type="text" width="200px" />
      </td>
      <td class="text-right">
        <q-skeleton type="text" width="100px" />
      </td>
    </tr>
    </tbody>
  </q-markup-table>
</template>

<script setup>
</script>

<style scoped>

</style>
