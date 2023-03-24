<template>
  <div class="q-mb-lg">
    <q-skeleton type="QBtn" width="180px" />
  </div>
  <q-markup-table>
    <thead>
    <tr>
      <th class="text-left">
        <q-skeleton type="text" width="15px" />
      </th>
      <th class="text-right">
        <q-skeleton type="text" width="24px" />
      </th>
      <th class="text-right">
        <q-skeleton type="text" width="80px" />
      </th>
    </tr>
    </thead>

    <tbody>
    <tr v-for="n in 5" :key="n">
      <td class="text-left">
        <q-skeleton type="text" width="15px" />
      </td>
      <td class="text-right">
        <q-skeleton type="text" width="500px" />
      </td>
      <td class="text-right">
        <q-skeleton type="text" width="65px" />
      </td>
    </tr>
    </tbody>
  </q-markup-table>
</template>
<script>

</script>
<style>

</style>
